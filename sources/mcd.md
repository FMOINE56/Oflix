# MCD

```
user:user_id, pseudo, password, role
write, 0N user, 11 review
review:review_id, content,rating,date
:

:
genre:genre_id, name
is_critized, 0N movie, 11 review
actor:actor_id, name, surname

:
belongs_to, 0N movie, 0N genre
movie:movie_id, title, duration, release_date, synopsis, summary, poster
role, 0N movie, 0N actor: role, credit_order

:
season:season_id, number_episodes, number_season
has, 0N movie, 11 season
:
```