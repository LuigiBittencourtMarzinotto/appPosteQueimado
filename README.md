# 🪔 Poste Queimado

Sistema web responsivo para registro de problemas de iluminação pública.

---

## ✅ Requisito único

**Docker Desktop** instalado no Windows:
👉 https://www.docker.com/products/docker-desktop/

---

## 🚀 Como rodar

Abra o **PowerShell** ou **CMD** dentro da pasta do projeto e rode:

```
docker-compose up -d --build
```

Aguarde ~3 minutos. O Docker vai automaticamente:
- Instalar todas as dependências PHP (composer install)
- Criar o banco de dados
- Rodar as migrations
- Inserir dados de teste

---

## 🌐 Acessar

| O quê | Link |
|---|---|
| 🌐 Sistema | http://localhost:8000 |
| 🗄️ phpMyAdmin | http://localhost:8080 |

---

## 👤 Contas de teste

| Tipo | E-mail | Senha |
|---|---|---|
| Usuário comum | joao@email.com | user123 |
| Administrador | admin@postequeimado.com | admin123 |

---

## 🛠️ Outros comandos úteis

```bash
# Parar os containers
docker-compose down

# Parar e apagar o banco (reset total)
docker-compose down -v

# Ver logs
docker-compose logs app

# Resetar banco de dados
docker-compose exec app php artisan migrate:fresh --seed
```
"# appPosteQueimado" 
