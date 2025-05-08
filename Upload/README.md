# 🛡️ DVWA - File Upload Vulnerability

Ce guide décrit l'exploitation de la vulnérabilité **File Upload** dans DVWA (`Low`, `Medium`, `High`) avec des exemples concrets et un webshell de type `c99/r57` simplifié.

---

## 🔎 Objectif

Uploader un **webshell PHP** dans l’application vulnérable DVWA pour :
- Exécuter des commandes système
- Télécharger des fichiers
- Supprimer des fichiers

---

## 🧬 Structure du shell (`shell.php`)
[shell-r57_c99.php](./shell-r57_c99.php)

➡️ Accessible à :

```
http://localhost:8080/dvwa/hackable/uploads/shell.php?cmd=whoami
```

---

## ⚙️ Outils utilisés

* [Burp Suite](https://portswigger.net/burp)
* `curl` (optionnel)
* Navigateur (Firefox, Chrome)

---

## 🔓 Niveau LOW

### 🔥 Vulnérabilité

* **Aucun filtre** sur le type ou l’extension du fichier.
* Upload direct d’un fichier `.php` est possible.

### 🎯 Exploitation

1. Crée `shell.php`
2. Envoie via le formulaire de DVWA
3. Le fichier sera accessible à :

   ```
   http://localhost:8080/dvwa/hackable/uploads/shell.php
   ```
4. Exécute une commande :

   ```
   http://localhost:8080/dvwa/hackable/uploads/shell.php?cmd=ls /
   ```

### ✅ Résultat attendu

Exécution de commandes système depuis l'interface web.

---

## 🔒 Niveau MEDIUM

### 🔥 Vulnérabilité

* Filtrage par **type MIME** (`image/jpeg`, `image/png`)
* Vérifie `$_FILES['uploaded']['type']` mais cela **peut être falsifié**

### 🎯 Exploitation avec Burp Suite
1. Renomme `shell.php` en `shell.php.jpg` | Mais pour faire plus simple on peut utiliser le même fichier php.
2. Upload depuis DVWA.
3. Intercepte la requête avec Burp Suite
4. Modifie le champ MIME :

   ```
   Content-Type: image/jpeg
   ```

   même si c’est un fichier PHP
5. Forward la requête

### ✅ Résultat attendu

* Le fichier est uploadé malgré son contenu PHP
* Accès à :

  ```
  http://localhost:8080/dvwa/hackable/uploads/shell.php?cmd=ls
  ```

### ⚠️ Astuce

Si Apache interprète `.php.jpg` comme du texte, essaye :

* `shell.pHp`
* `shell.php5`
* `shell.php.` (avec un point final)

---

## 🔐 Niveau HIGH

### 🔥 Vulnérabilité

* Vérification côté **serveur** ET **nom d'extension**
* Filtrage plus strict :

  * Extension vérifiée (`.jpg`, `.png`)
  * Type MIME validé
  * Upload vers un répertoire non accessible en exécution (parfois)

### 🎯 Exploitation possible (si config faible)

1. Crée une **image PNG valide avec code PHP à la fin** :

   ```bash
   cat image.png shell.php > payload.png
   ```

2. Renomme en `.php.png` ou `.png`

3. Upload avec Burp :

   * Faux `Content-Type`
   * Nom qui ressemble à une image

4. Accès :

   ```
   http://localhost:8080/dvwa/hackable/uploads/payload.php.png
   ```

📌 La **config Apache** ou PHP doit autoriser `.php.png`, ce qui est rare.

---

## 🧹 Comment Corriger ?

| Mesure                                                 | Description                                        |
| ------------------------------------------------------ | -------------------------------------------------- |
| Vérifier l'extension **et** le MIME                    | Avec `finfo_file()` (pas juste `$_FILES['type']`)  |
| Changer le nom du fichier                              | Générer un nom unique sans extension exécutable    |
| Mettre les fichiers dans un dossier **non exécutable** | Ex. `/uploads/` désactivé par `.htaccess`          |
| Interdire l’accès public direct                        | Sauvegarde et téléchargement via script uniquement |
| Scanner le contenu du fichier                          | Anti-virus, YARA, ou signatures de WebShell        |

---

## 🔗 Références

* [OWASP Unrestricted File Upload](https://owasp.org/www-community/vulnerabilities/Unrestricted_File_Upload)
* [PHP File Upload Docs](https://www.php.net/manual/en/features.file-upload.php)

---

## 👨‍💻 Auteur

* Gaby — Full-Stack & WebSec Apprentice
* Instagram: [@jeanga7](https://www.instagram.com/jeanga7)
* Github: [@jeanga7](https://www.github.com/jeanga7)

