# Decision Log - Tommi Lahtinen

## How to run it

```
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

Application:
http://localhost:8000

API:
http://localhost:8000/api

Run the test suite:

```
docker compose exec app php artisan test
```

## 1. What I built

- Product listing fetched from a Laravel API and rendered with Vue.
- Shopping cart supporting multiple products and quantity adjustments.
- Order creation with one or more line items.
- Orders persisted to MySQL.
- Product sale pricing with automatically calculated discount percentage.
- Frontend and backend validations.
- Docker development environment.
- Initial implementation to support multiple languages.
- A feature test covering backend validation for invalid order items.

## 2. What I deliberately cut, and why

- Authentication, user accounts, order history, product management, as they were outside the scope of the assignment.
- Product images and search/filtering to focus on the required order flow.
- Comprehensive automated test coverage, especially for the successful order flow, due to time constraints.
- Styling beyond a clean functional UI.

## 3. Key technical decisions

- Business logic for order creation and price calculation is implemented in a service class rather than controllers.
- The frontend only sends product numbers and quantities. Prices and totals are calculated on the backend to prevent client-side manipulation.
- Products expose a public product_no instead of database IDs.
- Request validation is performed on the backend, while frontend validation provides immediate user feedback.
- Sale percentages are calculated from the stored sale price instead of storing both values to avoid duplicated data.

## 4. Where I used AI, and what I changed

I used ChatGPT as a development assistant for discussing architecture, Docker configuration, code structure and for writing the initial tests.

I reviewed all generated code before using it. Some suggestions required modification or were discarded, particularly around Docker configuration, frontend component structure, and API responses, to better fit the project and assignment requirements and to align with my own views of what the app should look like.

For example I decided to add translation support, discount feature and immediate frontend validation even though they were not necessary to satisfy the core functional requirements. The generated solutions often solved the functional requirement, but I frequently refined them to improve usability, maintainability, and consistency with the architecture I wanted.

## 5. If I had 2 more hours

1. Expand the automated test coverage for the complete order flow.
2. Improve the UI to handle larger amount of products in a user-friendly way.
3. Replace the custom frontend validation with a dedicated validation library such as VeeValidate to improve maintainability.
4. Improve the translation system by using Vue I18n.

## 6. Known issues / shortcuts

- Automated test coverage is limited. Currently the custom feature test only covers invalid order item validation, and the successful order flow should be covered next.
- The frontend currently uses a simple JSON translation file instead of a full i18n solution.
- Currency formatting is currently hardcoded and should be localized (for example using Intl.NumberFormat).
- Styling is intentionally minimal and prioritizes functionality over appearance.
- There are a few remaining TODO comments documenting improvements that would be made in a production application.
