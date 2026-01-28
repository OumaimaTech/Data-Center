# Installation et Configuration

# Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL ou SQLite
- Node.js et NPM (pour les assets frontend)

### Étapes d'installation

1. **Installer les dépendances PHP**
```bash
composer install
```
2. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```
3. **Configurer la base de données**
Ouvrir le fichier `.env` et configurer la connexion à la base de données :
```
DB_CONNECTION=sqlite
DB_DATABASE=/chemin/absolu/vers/database/database.sqlite
```

Ou pour MySQL :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datacenter
DB_USERNAME=root
DB_PASSWORD=
```

4. **Créer la base de données et exécuter les migrations**
```bash
php artisan migrate
```

5. **Peupler la base de données (optionnel)**
```bash
php artisan db:seed
```

6. **Installer les dépendances frontend**
```bash
npm install
npm run build
```

7. **Démarrer le serveur de développement**
```bash
php artisan serve
```

L'application sera accessible à l'adresse : **http://127.0.0.1:8000**

## Comptes de test

Après avoir exécuté les seeders, vous pouvez utiliser ces comptes :

- **Administrateur**
  - Email: admin@datacenter.com
  - Mot de passe: password

- **Responsable technique**
  - Email: manager@datacenter.com
  - Mot de passe: password

- **Utilisateur interne**
  - Email: user@datacenter.com
  - Mot de passe: password

## Fonctionnalités principales

- Gestion des utilisateurs et des rôles
- Gestion des ressources (serveurs, équipements)
- Système de réservation
- Notifications en temps réel
- Tableau de bord selon le rôle
