<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\DTOs\V1\CustomerDTOV1;
use App\Filters\CustomerFilter;
use Illuminate\Http\JsonResponse;
use App\Services\Api\V1\CustomerServiceV1;
use App\Http\Controllers\Api\V1\ApiControllerV1;
use App\Http\Resources\Api\V1\Customer\CustomerResourceV1;
use App\Http\Requests\Api\V1\Customer\StoreCustomerRequestV1;
use App\Http\Requests\Api\V1\Customer\UpdateCustomerRequestV1;

class CustomerControllerV1 extends ApiControllerV1
{
    public function __construct(private CustomerServiceV1 $customerService) {}

    /* public function index(CustomerFilter $filters)
    {
        try {
            $perPage = request()->input('per_page', 10);
            $customers = $this->customerService->getAllCustomers($filters, $perPage);

            return $this->ok('Customers retrieved successfully', CustomerResourceV1::collection($customers));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    } */

    /* public function index(StoreCustomerRequestV1 $request): JsonResponse
    {
        $documentNumber = $request->validated()['document_number'];
        $token = $request->bearerToken();

        $customer = $this->customerService->findByCedulaWithAuth($documentNumber, $token, $request->ip());

        if (! $customer) {
            return response()->json([
                'message' => 'Cliente no encontrado o no autorizado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'message' => 'Cliente encontrado',
            'data' => $customer,
        ], Response::HTTP_OK);
    } */


    public function showByDocument(Request $request, $document)
    {
        $client = $request->get('api_client');

        if (!in_array('read_customers', $client->permissions ?? [])) {
            return response()->json(['message' => 'No tienes permisos'], 403);
        }

        $customer = Customer::where('document_number', $document)->first();

        if (!$customer) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return response()->json($customer);
    }


    public function store(StoreCustomerRequestV1 $request)
    {
        try {
            $dto = CustomerDTOV1::fromRequest(
                $request->validated()
            );
            $customer = $this->customerService->createCustomer($dto);
            return $this->ok('Customer created successfully', new CustomerResourceV1($customer));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function show(Customer $customer)
    {
        try {
            return $this->ok('Customer retrieved successfully', new CustomerResourceV1($customer));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function update(UpdateCustomerRequestV1 $request, Customer $customer)
    {
        try {
            $dto = CustomerDTOV1::fromRequest(
                $request->validated()
            );
            $customer = $this->customerService->updateCustomer($customer, $dto);
            return $this->ok('Customer updated successfully', new CustomerResourceV1($customer));
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->deleteCustomer($customer);
            return $this->ok('Customer deleted successfully');
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
