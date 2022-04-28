# karma Position

Each user in database has a karma score, the higher the karma score they have, the better ranking position they get. Usually, they
get a higher karma score by commenting and receiving likes from other users.

## API Reference

#### Get all items

```http
  GET /api/v1/user/{id}/karma-position/{numUsers?}
```

| Parameter  | Type     | Description                            |
| :--------- | :------- | :------------------------------------- |
| `id`       | `number` | **Required**. Id of user               |
| `numUsers` | `number` | **Optional**. number of users to fetch |

## How it's work:

-   Number of user mean the number of users will returned (Include the requested user).

-   The default number of user is 5.

-   If number of users is more the count of users, it will back to default value (5).

-   Number of users customizable, Example: if the number of users is 9, it will return 4 users before the requested user and 4 after him.

-   If the user has the first position, the API will show him first, and show the next 4 lower users in ranking.

-   If the user has the last position, the API will show the user at the end of the list and the 4 users are higher than him at the top.

-   For fills the database with fake data with 100,000 users:

```
php artisan migrate:fresh --seed
```
