## Installation

1. Clone this repo
2. Create db sqlite : touch /absolute/path/to/db.sqlite
3. Rename .env.example to .env
4. Change DB_DATABASE in .env
5. Run composer install
6. Migrate db php artisan migrate:fresh
7. Serve php artisan serve

## Endpoints

1. Create a new user

curl -X POST localhost:8000/api/register \
  -H "Accept: application/json" \
  -H "Content-type: application/json" \
  -d '{"name":"felix1","email":"felix1@example.com","password":"felix1","password_confirmation":"felix1"}'


2. Login

curl -X POST localhost:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-type: application/json" \
  -d '{"email":"felix1@example.com","password":"felix1"}'

3. Create a new loan

curl -X POST localhost:8000/api/loans \
  -H "Accept: application/json" \
  -H "Content-type: application/json" \
  -H "Authorization: Bearer insert_api_token_here" \
  -d '{"duration":100,"repayment_frequency":5,"interest_rate":0.1,"penalty_rate":0.5,"arrangement_fee":10,"total_loan":10000}'

4. Create a new repayment

curl -X POST localhost:8000/api/repayments \
  -H "Accept: application/json" \
  -H "Content-type: application/json" \
  -H "Authorization: Bearer insert_api_token_here" \
  -d '{"loan_id":1,"total":2210}'

5. View loan (with repayments)

curl -X GET localhost:8000/api/loans \
  -H "Accept: application/json" \
  -H "Authorization: Bearer insert_api_token_here"
