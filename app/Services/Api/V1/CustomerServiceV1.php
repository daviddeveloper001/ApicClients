<?php

namespace App\Services\Api\V1;

use App\Models\Customer;
use App\Exceptions\CustomerException;
use App\Repositories\V1\CustomerRepositoryV1;
use Illuminate\Http\Response;
use App\DTOs\V1\CustomerDTOV1;

class CustomerServiceV1
{
    public function __construct(private CustomerRepositoryV1 $customerRepository) {}

    public function getAllCustomers($filters, $perPage)
    {
        try {
            return Customer::filter($filters)->paginate($perPage);
        } catch (\Exception $e) {
            throw new CustomerException(
                'Failed to retrieve Customers',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }

    public function getCustomerById(Customer $customer)
    {
        try {
            $result = $this->customerRepository->find($customer);
            if (!$result) {
                throw new CustomerException('Customer not found', Response::HTTP_NOT_FOUND);
            }
            return $result;
        } catch (\Exception $e) {
            throw new CustomerException(
                'Failed to retrieve Customer',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }

    public function createCustomer(CustomerDTOV1 $dto)
{
    return $this->customerRepository->create($dto->toArray());
}

    public function updateCustomer(Customer $customer, CustomerDTOV1 $dto)
{
    return $this->customerRepository->update($customer, $dto->toArray());
}

    public function deleteCustomer(Customer $customer)
    {
        try {
            return $this->customerRepository->delete($customer);
        } catch (\Exception $e) {
            throw new CustomerException(
                'Failed to delete Customer',
                developerHint: $e->getMessage(),
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $e
            );
        }
    }
}