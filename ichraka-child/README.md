# Thème enfant — Association Ichraka

Site officiel de l'**Association Ichraka** (إشراقة) — Témara, Maroc.
Thème enfant Astra avec charte graphique orange/bleu/vert, support RTL arabe et CPT pour les opérations / projets / partenaires / témoignages.

> *« Ensemble, nous pouvons créer le changement. »*

---

## 📋 Prérequis

| Composant | Version minimale |
|---|---|
| WordPress | 6.5+ |
| PHP | 8.1+ |
| MySQL | 8.0 (ou MariaDB 10.5+) |
| Thème parent | [Astra](https://wordpress.org/themes/astra/) (gratuit) |

Hébergement cible : **Hostinger Business** (PHP 8.1, MySQL 8.0).

---

## 🚀 Installation

### 1. Préparer WordPress

```bash
# Via WP-CLI (recommandé)
wp core download --locale=fr_FR
wp config create --dbname=ichraka --dbuser=root --dbpass=secret
wp core install --url=https://ichraka.ma --title="Association Ichraka" \
    --admin_user=admin --admin_email=contact@ichraka.ma
```

### 2. Installer le thème parent Astra

```bash
wp theme install astra --activate
```

### 3. Déposer le thème enfant

Copier le dossier `ichraka-child/` complet dans :
```
wp-content/themes/ichraka-child/
```

Puis activer :
```bash
wp theme activate ichraka-child
```

### 4. Importer le contenu initial

```bash
wp eval-file wp-content/themes/ichraka-child/tools/import_content.php
```

> 💡 Le script est **idempotent** — relançable à volonté. Pour tout réinitialiser : ajouter `--reset`.

### 5. Installer les plugins recommandés

```bash
# Indispensables
wp plugin install contact-form-7 wordpress-seo --activate

# Pour les dons (choisir l'un OU l'autre)
wp plugin install give --activate
# OU
wp plugin install wpforms-lite --activate

# Pour le bilinguisme FR / AR (RTL)
wp plugin install polylang --activate
# OU WPML (premium)
```

### 6. Configurer les permaliens

```bash
wp rewrite structure '/%postname%/'
wp rewrite flush
```

Ou via **Réglages → Permaliens** → enregistrer.

---

## ⚙️ Configuration

Toutes les options sont stockées dans `wp_options`. Réglez-les via **Réglages → Général** ou via WP-CLI :

```bash
# Email contact
wp option update ichraka_contact_email "contact@ichraka.ma"

# Téléphone contact
wp option update ichraka_contact_phone "+212 5 37 XX XX XX"

# Adresse
wp option update ichraka_contact_address "Témara, Maroc"

# Carte Google Maps (mot-clé)
wp option update ichraka_gmap_query "Temara, Maroc"

# ID du formulaire Contact Form 7 (à utiliser sur la page Contact)
wp option update ichraka_cf7_id 42

# ID du formulaire de don GiveWP
wp option update ichraka_give_form_id 51

# OU ID du formulaire de don WPForms
wp option update ichraka_wpforms_donate_id 12

# RIB pour fallback don bancaire
wp option update ichraka_bank_name "Attijariwafa Bank"
wp option update ichraka_bank_rib "007 XXX XXXXXXXXXXXXXXXX XX"
```

---

## 🗂️ Structure du thème

```
ichraka-child/
├── style.css                       # Charte graphique + base CSS (header WP)
├── functions.php                   # Bootstrap (charge les modules /inc)
├── theme.json                      # Palette + typo pour Gutenberg/FSE
├── rtl.css                         # (vide — chargé par WP en mode RTL)
├── README.md                       # Ce fichier
│
├── inc/
│   ├── theme-setup.php             # Astra hooks, menus, sidebars, body class
│   ├── enqueue.php                 # Scripts + styles + Google Fonts
│   ├── custom-post-types.php       # CPT operation, projet, partenaire, temoignage
│   ├── widgets.php                 # 3 widgets : Don, Opérations, Compteur
│   ├── shortcodes.php              # 8 shortcodes éditeurs
│   ├── template-loader.php         # Sélecteur de templates dans l'admin
│   └── seo.php                     # Meta tags + JSON-LD + breadcrumbs
│
├── templates/
│   ├── page-accueil.php            # Hero + actions + compteurs + don
│   ├── page-operations.php         # Grille toutes les opérations
│   ├── page-don.php                # Formulaire de don (Give/WPForms/RIB)
│   ├── page-contact.php            # CF7 + carte Google Maps
│   ├── page-equipe.php             # Bureau de l'association
│   └── page-projets.php            # Projets avec barres de progression
│
├── template-parts/                 # (réservé pour futurs partials)
│
├── assets/
│   ├── css/
│   │   ├── components.css          # Footer, partenaires, badges
│   │   └── rtl.css                 # Overrides arabe
│   ├── js/
│   │   └── ichraka.js              # Compteurs, scroll, lightbox
│   └── images/
│       └── hero-bg.svg             # Soleil stylisé Ichraka
│
├── languages/                      # .pot / .po pour traductions
│
└── tools/
    └── import_content.php          # Script WP-CLI d'import initial
```

---

## 🎨 Charte graphique

| Token | Valeur |
|---|---|
| `--ichraka-primary` | `#E8780A` (orange soleil) |
| `--ichraka-primary-dark` | `#C4640A` |
| `--ichraka-secondary` | `#2980B9` (bleu) |
| `--ichraka-accent` | `#27AE60` (vert — boutons de don) |
| Police titres | Montserrat (Google Fonts) |
| Police texte | Open Sans |
| Police arabe | Cairo |

Toutes les variables sont exposées en CSS custom properties (`:root`) **et** dans `theme.json` (palette/typo Gutenberg).

---

## 🧩 Shortcodes disponibles

| Shortcode | Description |
|---|---|
| `[ichraka_hero title="..." tagline="..."]` | Section hero avec CTA |
| `[ichraka_counter number="300" label="..." suffix="+"]` | Un compteur |
| `[ichraka_counters]` | Grille de compteurs préconfigurée |
| `[ichraka_operations limit="3"]` | Grille d'opérations |
| `[ichraka_team]` | Bureau de l'association |
| `[ichraka_testimonials limit="3"]` | Témoignages |
| `[ichraka_partners]` | Logos des partenaires |
| `[ichraka_donate_button text="..."]` | Bouton de don stylisé |

---

## 🌍 Bilinguisme (FR / AR)

1. Installer **Polylang** : `wp plugin install polylang --activate`
2. Réglages → Langues → ajouter **Français** (par défaut) puis **العربية / Arabic** (RTL coché)
3. Pour chaque page/opération, traduire via le métabox Polylang
4. Le thème détecte automatiquement `is_rtl()` et charge `assets/css/rtl.css`

---

## 🛠️ Maintenance par les non-développeurs

Le thème est conçu pour être **100% éditable depuis l'admin WordPress** :

| Vous voulez modifier… | Allez dans… |
|---|---|
| Une page existante | Pages → \[page concernée\] |
| Le bureau (équipe) | `inc/shortcodes.php` (filtre `ichraka_team_members`) ou ajouter un CPT custom |
| Les compteurs de la home | `inc/shortcodes.php` (filtre `ichraka_counters`) |
| Une opération | Opérations → \[opération concernée\] |
| Un témoignage | Témoignages → \[témoignage\] |
| Le menu | Apparence → Menus |
| Le logo | Apparence → Personnaliser → Identité du site |
| Les couleurs | Apparence → Personnaliser → Couleurs |

---

## 📈 Performance

- ✅ Google Fonts en `display=swap` + `preconnect`
- ✅ Pas de jQuery côté front (JS vanilla)
- ✅ Lazy reveal via `IntersectionObserver`
- ✅ Images responsive avec `add_image_size()`
- ✅ Emojis désactivés
- ✅ CSS modulaire (~12 Ko gzip total)

Objectif Lighthouse : **mobile > 85**, **desktop > 95**.

---

## 🔒 Sécurité

- Toutes les sorties échappées (`esc_html`, `esc_url`, `esc_attr`)
- Métaboxes avec nonces + capabilities
- Aucun appel `eval()` ou `unserialize()` non sûr
- Headers CSP recommandés via `.htaccess` (à ajouter côté hébergement)

---

## 📞 Contact technique

- **Trésorier / mainteneur technique** : Khalil FERHAT — `khalilferhat@gmail.com`
- **Site actuel (Joomla — à migrer)** : https://www.ichraka.ma

---

## 📜 Licence

GPL-2.0-or-later, comme WordPress et Astra.
