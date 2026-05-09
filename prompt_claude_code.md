# Prompt Claude Code — Nouveau site Ichraka

> Copie-colle ce prompt directement dans Claude Code pour générer le nouveau site.

---

## PROMPT À DONNER À CLAUDE CODE

```
Tu es un expert WordPress/développement web. Tu vas construire le nouveau site complet de l'Association Ichraka (Maroc), en remplacement d'un ancien site Joomla v3 obsolète.

## CONTEXTE
- Association à but non lucratif basée à Témara, Maroc
- Fondée en 2008, axée sur l'éducation et la santé des enfants défavorisés
- Tagline : "Ensemble, nous pouvons créer le changement"
- Le site doit être maintenable par des non-développeurs membres de l'association

## STACK TECHNOLOGIQUE CHOISIE
- WordPress (dernier version stable)
- Thème enfant basé sur Astra (léger, rapide, gratuit)
- Plugin Elementor Free ou FSE (Full Site Editing) pour la mise en page
- Plugin Contact Form 7 pour le formulaire de contact
- Plugin GiveWP ou WPForms pour les dons en ligne
- Plugin WPML ou Polylang pour le bilinguisme français/arabe (RTL)

## STRUCTURE DES PAGES À CRÉER

### Pages principales :
1. **Accueil** — Hero avec tagline + 3 actions phares + appel aux dons
2. **Qui Sommes Nous** (page parent)
   - Historique
   - Notre Mission
   - Nos Objectifs
   - Notre Charte
   - Communication interne
   - Responsabilités des membres
3. **Nos Actions** — Vue d'ensemble de toutes les actions
4. **Opérations** (page parent)
   - Opération Cartables
   - Opération Vêtements
   - Opération Lunettes
5. **Parrainage** — Programme de parrainage d'enfants
6. **Nos Projets** — Projets en cours
7. **Contactez-nous** — Formulaire de contact + carte Google Maps
8. **Faire un Don** — Page de donation

## CONTENU À INTÉGRER

### Identité :
- Nom : Association Ichraka (aussi appelée Sunrise)
- Tagline : "Ensemble, nous pouvons créer le changement"
- Fondée : 27 mars 2008 à Témara, Maroc
- Statut : Association à but non lucratif, apolitique et indépendante

### Bureau :
- Safae BOUJENDAR : Présidente
- Asmae OUAZZANI CHAHDI : Vice-présidente
- Brahim FERHAT : Secrétaire Général
- Khalil FERHAT : Trésorier
- Ikrame OUAHBI : Trésorier Adjoint
- Fouzia KHALDI : Conseillère
- Imane CHERRAT : Conseillère

### Texte Historique :
Ichraka est une association de développement social et culturel apolitique et indépendante à but non lucratif qui œuvre, principalement, dans les domaines du soutien scolaire et de la santé au profit des enfants dans le besoin.

Suite à une visite organisée à l'orphelinat Dar Al Atfal de Témara en octobre 2007, un groupe de bénévoles a décidé de se réunir pour aider ces enfants. C'est ainsi que le 27 mars 2008 l'Association Ichraka a vu le jour.

### Texte Mission :
Entreprendre ou contribuer à la réalisation de projets couvrant les domaines de l'éducation et de la santé de l'enfance défavorisée.

Objectifs : soutien moral et financier aux orphelins, scolarisation, lutte contre la déperdition scolaire, soins sanitaires, amélioration des conditions de vie, développement de la solidarité.

Cibles : Orphelinat Dar Al Atfal (Témara), Orphelinat Dar Al Fatat (Témara), Dar Attaliba (Sidi Yahya Zaïr), Écoles rurales Al Hawamid.

### Opération Cartables :
Offrir un cartable complet (trousse, cahiers, manuels, ardoise) à près de 300 enfants des régions rurales démunies pour chaque rentrée scolaire depuis 2008.

### Opération Vêtements :
Depuis 2007, collecte de fonds pour l'achat de vêtements d'hiver au profit des orphelins et des élèves de l'école rurale Al Hawamid.

### Opération Lunettes :
Visites d'ophtalmologues et distribution de lunettes optiques aux enfants de Dar Al Atfal et des écoles rurales de Témara. Soutenu par des médecins spécialistes bénévoles.

## CHARTE GRAPHIQUE

- **Couleur principale** : Orange #E8780A (couleur du soleil du logo)
- **Couleur secondaire** : Bleu #2980B9
- **Couleur accent** : Vert #27AE60 (pour les boutons de don)
- **Police titres** : Montserrat ou Poppins (Google Fonts, sans-serif moderne)
- **Police texte** : Open Sans ou Lato
- **Logo** : Soleil stylisé orange avec texte "إشراقة Ichraka" (le logo actuel est à https://www.ichraka.ma/images/stories/logo.png)
- **Style** : Moderne, chaleureux, professionnel — inspiré des sites d'ONG internationales

## FONCTIONNALITÉS REQUISES

1. **Design responsive** mobile-first (60% des visites sont sur mobile)
2. **Formulaire de contact** avec validation
3. **Bouton de don en ligne** visible sur toutes les pages (header + homepage)
4. **Section actualités/blog** pour publier des news sur les opérations
5. **Galerie photos** pour chaque opération
6. **Compteurs animés** sur la homepage (ex: 300 enfants aidés, 15 ans d'existence, etc.)
7. **Témoignages** section
8. **Support RTL arabe** (langue secondaire)
9. **SEO optimisé** (balises meta, sitemap XML, breadcrumbs)
10. **Performance** : score Lighthouse > 85

## CE QUE TU DOIS PRODUIRE

1. Un fichier `functions.php` pour le thème enfant Astra avec :
   - Enregistrement des scripts et styles
   - Support des Custom Post Types (Opérations, Projets, Partenaires)
   - Widgets personnalisés

2. Un fichier `style.css` avec la charte graphique complète

3. Des templates de pages WordPress (`page-accueil.php`, `page-operations.php`, etc.)

4. Un fichier `theme.json` pour le Full Site Editing avec la palette de couleurs et typographie

5. Un script WP-CLI `import_content.php` pour importer automatiquement tout le contenu des pages

6. Un fichier `README.md` avec les instructions d'installation et de configuration

## INSTRUCTIONS TECHNIQUES

- Utilise WordPress 6.5+
- Compatible PHP 8.1+
- Utilise les hooks WordPress standards (add_action, add_filter)
- Pas de plugins premium requis — tout en gratuit/open source
- Hébergement cible : Hostinger Business (PHP 8.1, MySQL 8.0)
- Langue principale : Français, secondaire : Arabe (RTL)

## COMMENCER PAR

1. D'abord créer la structure de fichiers du thème enfant
2. Ensuite le fichier style.css avec la charte graphique
3. Puis les templates de pages
4. Enfin le script d'import du contenu

Lance-toi maintenant et produis tous les fichiers nécessaires.
```

---

## NOTES POUR KHALIL

### Comment utiliser ce prompt :
1. Ouvre **Claude Code** dans ton terminal : `claude`
2. Crée un dossier pour le projet : `mkdir ichraka-wordpress && cd ichraka-wordpress`
3. Colle le prompt ci-dessus
4. Claude Code va générer tous les fichiers du thème

### Étapes après la génération :
1. Installe WordPress sur Hostinger
2. Installe le thème parent Astra
3. Upload le thème enfant généré
4. Active le thème
5. Lance le script d'import du contenu
6. Configure les plugins (Contact Form 7, GiveWP)
7. Récupère les images depuis le cPanel de l'ancien site (`/public_html/images/`)
8. Transfère le domaine ichraka.ma vers le nouvel hébergeur

### Coût estimé du nouveau site :
- Hébergement Hostinger Business : ~$3/mois
- Domaine (déjà possédé) : 0
- Thème Astra + plugins gratuits : 0
- **Total : ~36$/an**

### Alternatives no-code si tu préfères sans développement :
- **Webflow** : Très beau design, $14/mois, exportable
- **Wix** : Gratuit pour commencer, $13/mois pour domaine custom
- **Squarespace** : Beau design, $16/mois

---

*Généré par Claude (Cowork) le 07/05/2026*
