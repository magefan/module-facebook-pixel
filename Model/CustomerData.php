<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\FacebookPixel\Model;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\DataObject;

class CustomerData
{
    const CUSTOMER_DATA = [
        'fn' => "firstName",
        'ln' => "lastName",
        'em' => "email",
        'db' => "dob",
        'ge' => "gender",
        'ph' => "telephone",
        'zp' => "postCode",
        'ct' => "city",
        'st' => "region",
        'country' => "countryId",
    ];

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var Session
     */
    private $session;

    /**
     * Pixel constructor.
     *
     * @param Session $session
     * @param CustomerRepositoryInterface $customerRepository
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        Session                     $session,
        CustomerRepositoryInterface $customerRepository,
        AddressRepositoryInterface  $addressRepository
    )
    {
        $this->session = $session;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @return array
     */
    public function getCustomerData()
    {
        $customerPixelData = [];
        if ($this->session->isLoggedIn()) {
            try {
                $customerId = $this->session->getCustomer()->getId();
                $customer = $this->customerRepository->getById($customerId);
                $defaultShippingId = $customer->getDefaultShipping();
                $address = $defaultShippingId ? $this->addressRepository->getById($defaultShippingId) : new DataObject();
                foreach (self::CUSTOMER_DATA as $parameter => $customerData) {
                    $method = 'get' . ucfirst($customerData);
                    if (method_exists($address, $method)) {
                        $customerPixelData[$parameter] = $this->prepareCustomerData($customerData, $address->$method());
                    } elseif (method_exists($customer, $method)) {
                        $customerPixelData[$parameter] = $this->prepareCustomerData($customerData, $customer->$method());
                    }

                    if (isset($customerPixelData[$parameter]) && empty($customerPixelData[$parameter])) {
                        unset($customerPixelData[$parameter]);
                    }
                }
            }catch (\Exception $exception){
                return $customerPixelData;
            }
        }

        return $customerPixelData;
    }

    /**
     * @param $parameter
     * @param $data
     * @return string
     */
    private function prepareCustomerData($parameter, $data)
    {
        if('region' == $parameter) {
            $data = $data->getRegionCode();
        }

        if (in_array($parameter, ['telephone', 'dob'])) {
            $data = str_replace(['-', '(', ')', ' '], "", $data);
        }

        if ('gender' == $parameter) {
            $data = ($data == 1) ? 'f' : 'm';
        }

        $preparesString = strtolower(trim($data));

        return mb_convert_encoding($preparesString, "UTF-8");
    }
}
