# DataCenter - Gestion de Ressources

Une application web pour gérer les ressources de votre data center, développée avec Laravel 11.

## C'est quoi ce projet ?

DataCenter, c'est une plateforme complète qui vous permet de gérer facilement vos ressources informatiques. Vous pouvez gérer vos utilisateurs, réserver des serveurs, suivre les incidents, planifier les maintenances et bien plus encore.

Concrètement, avec cette application vous pouvez :
- Gérer vos utilisateurs avec différents niveaux d'accès
- Organiser vos ressources (serveurs, équipements réseau, etc.)
- Réserver des ressources pour vos projets
- Déclarer et suivre les incidents
- Planifier les maintenances
- Recevoir des notifications en temps réel
- Consulter des statistiques d'utilisation

## Les fonctionnalités

### Gestion des utilisateurs
On a mis en place un système d'authentification complet avec quatre types de rôles : Administrateur, Responsable technique, Utilisateur interne et Invité. Chaque rôle a ses propres permissions. Les invités peuvent même demander la création d'un compte qui sera validé par un admin.

### Gestion des ressources
Créez, modifiez ou supprimez vos ressources facilement. Vous pouvez les organiser par catégories, suivre leur statut (Disponible, Réservé, En maintenance, Hors service) et assigner un responsable technique pour chacune.

### Réservations
Besoin d'un serveur pour votre projet ? Faites une réservation avec les dates de début et fin. Le responsable technique validera votre demande. Vous pouvez aussi consulter l'historique de toutes vos réservations.

### Incidents
Un problème sur une ressource ? Déclarez un incident avec le niveau de priorité approprié (Faible, Moyenne, Haute, Critique). Le système permet de suivre l'évolution de chaque incident jusqu'à sa résolution.

### Maintenances
Planifiez vos périodes de maintenance en avance. Pendant ces périodes, les réservations sont automatiquement bloquées et les utilisateurs concernés sont notifiés.

### Notifications
Restez informé en temps réel de tout ce qui se passe : nouvelles réservations, incidents, maintenances planifiées, etc.

### Statistiques
Les administrateurs ont accès à un tableau de bord complet avec des statistiques sur l'utilisation des ressources, les réservations, les incidents et les maintenances.

## Ce dont vous avez besoin

Avant de commencer, assurez-vous d'avoir installé :
- PHP 8.2 ou plus récent
- Composer
- MySQL 5.7+ ou SQLite
- Node.js 16+ et NPM
- Git

## Installation

### Étape 1 : Récupérer le projet
```bash
git clone <url-du-repository>
cd dataCenter
```

### Étape 2 : Installer les dépendances
```bash
composer install
```

### Étape 3 : Configuration de base
```bash
cp .env.example .env
php artisan key:generate
```

### Étape 4 : Configurer votre base de données

Ouvrez le fichier `.env` et choisissez votre configuration :

**Pour SQLite (plus simple pour débuter) :**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/chemin/absolu/vers/database/database.sqlite
```

**Pour MySQL :**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datacenter
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### Étape 5 : Créer la base de données

**Si vous utilisez SQLite :**
```bash
touch database/database.sqlite
```

**Si vous utilisez MySQL :**
```bash
mysql -u root -p
CREATE DATABASE datacenter;
exit;
```

### Étape 6 : Créer les tables
```bash
php artisan migrate
```

### Étape 7 : Ajouter des données de test
```bash
php artisan db:seed
```

Cette commande va créer des utilisateurs de test, des catégories et quelques ressources pour que vous puissiez commencer à explorer l'application.

### Étape 8 : Assets frontend (optionnel)
```bash
npm install
npm run build
```

### Étape 9 : Lancer l'application
```bash
php artisan serve
```

Voilà ! Rendez-vous sur **http://127.0.0.1:8000** pour accéder à l'application.

## Comptes de test

Une fois les données de test chargées, vous pouvez vous connecter avec ces comptes :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@datacenter.com | password |
| Responsable technique | manager@datacenter.com | password |
| Utilisateur interne | user@datacenter.com | password |

## Organisation du projet

```
dataCenter/
├── app/                       # Le cœur de l'application
│   ├── Console/Commands/      # Commandes personnalisées
│   ├── Http/Controllers/      # Logique métier
│   ├── Models/                # Modèles de données
│   └── Providers/             # Services
├── config/                    # Configuration
├── database/                  # Migrations et seeders
├── public/                    # Fichiers accessibles (CSS, JS)
├── resources/views/           # Templates Blade
├── routes/                    # Définition des routes
└── storage/                   # Fichiers générés
```

## Stack technique

On a utilisé :
- **Laravel 11** pour le backend
- **Blade Templates** avec **Bootstrap 5** pour le frontend
- **MySQL** ou **SQLite** pour la base de données
- Le système d'authentification natif de Laravel

## Les différents rôles

### Administrateur
C'est le super-utilisateur. Il a accès à tout : gestion des utilisateurs, des catégories, validation des demandes de compte, statistiques globales, etc.

### Responsable technique
Il gère les ressources qui lui sont assignées, valide les réservations, traite les incidents et planifie les maintenances.

### Utilisateur interne
Il peut consulter les ressources disponibles, faire des réservations, déclarer des incidents et consulter son historique.

### Invité
Accès limité en lecture seule. Il peut consulter les ressources disponibles et faire une demande de création de compte.

## Quelques commandes utiles

```bash
# Réinitialiser complètement la base de données
php artisan migrate:fresh --seed

# Créer un nouveau contrôleur
php artisan make:controller NomController

# Créer un modèle avec sa migration
php artisan make:model NomModele -m

# Créer une migration
php artisan make:migration nom_de_la_migration

# Créer un seeder
php artisan make:seeder NomSeeder

# Vider les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Besoin d'aide ?

Si vous rencontrez un problème ou si vous avez des questions, n'hésitez pas à contacter l'équipe de développement.

---

Développé avec ❤️ pour faciliter la gestion des ressources du data center.
