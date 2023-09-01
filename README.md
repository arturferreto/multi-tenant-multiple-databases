# mtmd
Implementing multi-tenancy in Laravel applications using multiple databases.

```postgresql
  CREATE DATABASE landlord;
```

Comandos:
```bash
  sail artisan landlord:migrate --fresh --seed;
  sail artisan tenant:migrate --fresh --seed;
```
