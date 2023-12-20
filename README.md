# LOOP Backend Developer Test

[Details](https://backend-developer.view.agentur-loop.com/#/?id=loop-backend-developer-test)

## Notes:
* I have changed/updated the field names/convention as I felt is better. (eg. `FirstName` => `first_name`)
* I have kept the logic accurately to the task description except where I feel changes are necessary for improvement of the solution.
* I have skipped the security and authentication of the API because of time.
  * A `jwt` token or `passport` oauth MW could be used.
* I have added test cases for some not all to not spend too much time on it. 
  * The other way to do this task is to implement TDD but tests are written at the end to showcase my expertise briefly.

## Files to assess:

* [app/Exceptions/Handler.php](https://github.com/mubasharkk/loop-agentur/blob/main/app/Exceptions/Handler.php)
* [app/Http/Controllers/Api/OrdersController.php](https://github.com/mubasharkk/loop-agentur/blob/main/app/Http/Controllers/Api/OrdersController.php)
* [app/Services/](https://github.com/mubasharkk/loop-agentur/tree/main/app/Services)
* [app/Models](https://github.com/mubasharkk/loop-agentur/tree/main/app/Models)
___
## Time Assessments

| Task  | Time Estimated | Actual Time  | Description |
|---|---|---|---|
|  Laravel Setup + Git | 00:20  | <span style="color:green">00:10</span>   |    |
|  Setup docker via sail | 00:15  |  <span style="color:green">00:05</span> + 00:12 |  Additional time for creating docker containers |
|  Create `customers` table migration + model | 00:20  | <span style="color:green">00:12</span>  | All models+migrations are done in 38 mins |
|  Create `products` table migration + model| 00:20  |   <span style="color:green">00:14</span> |  |
|  Create `orders` table migration + model | 00:20  |   <span style="color:green">00:12</span> |  |
|  Create `masterdata` import command + activity Logging | 02:00  | <span style="color:green">01:49</span>  |  |
|  Create GET `products` list endpoint | 00:15  | <span style="color:green">00:10</span>  |  |
|  Create GET `products/{id}` endpoint | 00:15  |  <span style="color:green">00:05</span> |  |
|  Create GET `customers` list endpoint | 00:15  |  <span style="color:green">00:05</span> |  |
|  Create GET `customers/{id}` endpoint | 00:15  |  <span style="color:green">00:05</span> |  |
|  JsonResponse exception handling | 00:10  | <span style="color:green">00:12</span>  |  |
|  Create CRUD `orders` controller + Implement orders domain service  | 01:30  | <span style="color:red">02:14</span>  |  |
|  Implement payment endpoint | 00:45  |  <span style="color:red">00:50</span> |  |
|  Integrate for payment service | 00:45  | <span style="color:green">00:18</span>  |  |
|  Adding test cases for some endpoints | 01:45  | <span style="color:red">02:13</span>  |  |
___

**Note:** The smaller task actual time is just a rough noted time. Last 4 tasks are the important ones that took time.

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

./vendor/bin/sail artisan test tests/Feature/TestOrdersApiEndpoints.php
```
