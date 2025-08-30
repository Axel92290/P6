# ❄️ SnowTricks

Projet réalisé dans le cadre du parcours **Développeur d'application - PHP/Symfony** (OpenClassrooms).  
SnowTricks est une plateforme communautaire dédiée au **partage de figures de snowboard**.  
Les utilisateurs peuvent consulter, créer, éditer et supprimer des tricks, ainsi que commenter et illustrer avec des photos et vidéos.

---

## 🚀 Fonctionnalités principales

- Authentification (inscription, connexion, mot de passe oublié)  
- Gestion des rôles (`ROLE_USER`, `ROLE_ADMIN`)  
- Création / édition / suppression de tricks (seulement par l’auteur ou un admin)  
- Ajout de photos et vidéos sur un trick (via formulaire et modales)  
- Système de commentaires  
- Affichage des tricks en liste paginée avec bouton **"Load More"**  
- Page de détail d’un trick  
- Protection CSRF et sécurité par **Voters** (seul l’auteur ou l’admin peut modifier/supprimer)  

---

## 🛠️ Prérequis

- PHP **8.2+**
- Composer **2+**
- Symfony CLI (recommandé)
- MySQL/MariaDB
- Node.js + npm (ou Yarn) pour compiler les assets (Bootstrap, JS, CSS)

---

## ⚙️ Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/ton-profil/snowtricks.git
   cd snowtricks
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Configurer l’environnement**
   - Copier le fichier `.env` → `.env.local`
   - Modifier la ligne `DATABASE_URL` avec vos identifiants MySQL :
     ```
     DATABASE_URL="mysql://user:password@127.0.0.1:3306/snowtricks"
     ```

4. **Créer la base de données et exécuter les migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Charger les fixtures**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

---

## 🔑 Comptes de test

- 👤 **Utilisateur simple**
  - Email : `user1@test.com`
  - Mot de passe : `Octest123*`
  - Rôle : `ROLE_USER`

- 👑 **Administrateur**
  - Email : `admin@test.com`
  - Mot de passe : `admin@test.com`
  - Rôle : `ROLE_ADMIN`

---

## 📸 Utilisation

- La page d’accueil affiche les tricks avec un bouton **"Load More"**.  
- Chaque trick a :
  - Une image à la une  
  - Un nom  
  - Des boutons d’action (voir, éditer, supprimer) selon les droits  
- La page détail permet :
  - de voir les photos/vidéos associées  
  - de poster des commentaires (si connecté)  
  - d’ajouter/modifier/supprimer des médias (auteur/admin)  

---

## 🔒 Sécurité

- Les routes sensibles sont protégées par **Voters** :
  - Seul l’auteur du trick ou un admin peut le modifier/supprimer.  
- Protection CSRF pour tous les formulaires.  
- Mot de passe hashé via `password_hasher`.

---

## 📝 Auteur

Projet réalisé par **Axel Chasseloup** en **août 2025** dans le cadre de la formation OpenClassrooms.
