# LOOP Backend Developer Test

[Details](https://backend-developer.view.agentur-loop.com/#/?id=loop-backend-developer-test)

## Notes:
* I have changed/updated the field names/convention as I felt is better. (eg. `FirstName` => `first_name`)
* I have kept the logic accurately to the task description except where I feel changes are necessary for improvement of the solution.
* I have skipped the security and authentication of the API because of time.
  * A `jwt` token or `passport` oauth MW could be used.

## Files to assess:

* [app/Exceptions/Handler.php](https://github.com/mubasharkk/loop-agentur/blob/main/app/Exceptions/Handler.phphttps://github.com/mubasharkk/loop-agentur/blob/main/app/Exceptions/Handler.php)
* [app/Http/Controllers/Api/OrdersController.php](https://github.com/mubasharkk/loop-agentur/blob/main/app/Http/Controllers/Api/OrdersController.php)
* [app/Services/](https://github.com/mubasharkk/loop-agentur/tree/main/app/Services)
* [app/Models](https://github.com/mubasharkk/loop-agentur/tree/main/app/Models)
___
## Time Assessments

| Task  | Time Estimated | Actual Time  | Description |
|---|---|---|---|
|  Laravel Setup + Git | 00:20  | 00:10   |    |
|  Setup docker via sail | 00:15  |  00:05 + 00:12 |  Additional time for creating docker containers |
|  Create `customers` table migration + model | 00:20  | 00:12  | All models+migrations are done in 38 mins |
|  Create `products` table migration + model| 00:20  |   00:14 |  |
|  Create `orders` table migration + model | 00:20  |   00:12 |  |
|  Create `masterdata` import command + activity Logging | 02:00  | 01:49  |  |
|  Create GET `products` list endpoint | 00:15  | 00:10  |  |
|  Create GET `products/{id}` endpoint | 00:15  |  00:05 |  |
|  Create GET `customers` list endpoint | 00:15  |  00:05 |  |
|  Create GET `customers/{id}` endpoint | 00:15  |  00:05 |  |
|  JsonResponse exception handling | 00:10  | 00:12  |  |
|  Create CRUD `orders` controller + Implement orders domain service  | 01:30  | 02:14  |  |
|  Implement payment endpoint | 00:45  |  00:50 |  |
|  Integrate for payment service | 00:45  | 00:18  |  |
|  Adding test cases for all endpoints | 01:45  |   |  |
___

## Setup & Starting Laravel Sail

**Important:** !! Docker must be installed !!

Clone the repository and run the following command:

```
cp .env.example .env

composer install

./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate

./vendor/bin/sail artisan migrate
```
---
### Importing Master Data from given source

```
./vendor/bin/sail artisan import:master-data
```
---

## API Documentation

**Root Url + Prefix:** `http://localhost/api`

| Endpoint  | Params | Description  |
|---|---|---|
|  `GET /products` |   | List all products   | 
|  `GET /products/{id}` | `id`: Product Id  | Show customer by ID   |   
|  `GET /customers` |   | List all customers   |  
|  `GET /customers/{id}` | `id`: Customer Id  | Show  customer by ID   | 
|  `GET /orders` | `customer_id`: Customer ID  | List all orders of a customers   |   
|  `POST /customers` |  `customer_id`: Customer ID <br/> `product_id`: Product ID | Create a new order   | 
|  `PUT /customers/{id}/add` | `id`: Order ID <br/>`product_id`: Product ID  | Add product to an order   |  
|  `DELETE /customers/{id}/remove` | `id`: Order ID <br/>`product_id`: Product ID  | Remove product from an order   |  
|  `DELETE /customers/{id}` | `id`: Order ID  | Delete an order   |   
|  `POST /customers/{id}/pay` | `id`: Order Id  | Pay the order via LoopAgentur payment API   |    
---

### Running tests

```
./vendor/bin/sail artisan test
```
