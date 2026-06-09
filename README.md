# Setup
- PHP 8.2 minimum
- DB MySQL 8.0. Si autre db, re-générer les migrations
- Front via assets mapper. Lancer ```php bin/console importmap:install``` pour installer les deps

# Presentation
Fonctionnalité "Règle de génération de requête".

Extraction d'une fonctionnalité d'un projet d'un de nos clients. (Lissée et simplifiée pour les besoins du test)

Elle est aujourd'hui très lié à un cas d'usage : l'agrafage automatique de document.

On souhaite pouvoir la réutiliser pour d'autre usages, par exemple signature la automatique de document.

Une configuration (ex: StaplingConfig) contient une collection de règles (ex: StaplingRule).

Une configuration est traitée par un générateur qui produit une requête à partir de la collection de règle.

La configutation et la collection de règles est administrable depuis une interface.


# Objectif du test
Proposer une refactorisation de la fonctionnalité pour permettre de la réutiliser dans d'autre cas d'usage sans dupliquer la base logique.

Il faut prévoir la possibilité d'étendre/customiser les règles selon les usages.

Exemple: On veut créer une nouvelle configuration SignConfig qui contient aussi une collection de règles.
