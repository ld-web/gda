# REFACTORISATION

> [!NOTE]
> Taux global d'utilisation IA : 3/10.
> 0 : Pas du tout d'IA, 10 : Travail complètement délégué à une IA

## Observations initiales

Structure full-stack, application webapp, v7.2. Pas de tests existants, pas de fixtures.

Différents niveaux sur lesquels on interviendra si on veut de nouvelles configurations et de nouvelles règles :

- Modèles (entités) : si on veut de nouvelles configurations et de nouvelles règles avec leur propre structure de données
- Contrôleurs : pour administrer les configurations et les règles
- Formulaires (`src/Form`) : définira la structure de saisie dans l'interface
- Services : Générateur de requête

> [!NOTE]
> La logique existante est présentée comme une "base logique". On va donc arbitrer les choix qui suivent en se basant sur cette logique pour l'étendre et la rendre personnalisable. Il est cependant compliqué de se projeter davantage sur les cas d'utilisation futurs qui pourraient apparaître, auquel cas un couplage trop fort ou des partis pris sur des abstractions pourraient compliquer l'intégration de futurs cas.

## Analyse

Il semble que le système existant fonctionne sur un mode "entrée/sortie" : à partir d'une collection de règles attachées à une configuration (entrée), on génère une requête au format texte (sortie).

On a un service `StaplingConfigQueryGenerator`, qui va s'appuyer sur un `QueryBuilder` pour générer une `Query`. C'est la `Query` qui contient en interne la requête au format texte, accessible via un simple getter.

Le but est donc de pouvoir se baser sur le `StaplingConfigQueryGenerator` pour en tirer d'autres générateurs, par exemple `SignConfigQueryGenerator`, avec sa logique personnalisable.

## Préparation

Avant de "tout casser", j'ai décidé d'implémenter quelques tests unitaires pour valider le fonctionnement actuel. Le but n'est pas d'arriver à 100% de couverture mais d'avoir quelques portes installées. Par ailleurs, j'ai un peu mieux saisi le fonctionnement des classes en écrivant leurs tests. Je n'ai pas voulu y passer trop de temps non plus donc ils restent complètement perfectibles.

Des tests ont été mis en place pour les classes `QueryBuilder` et `StaplingConfigQueryGenerator` : les deux que j'ai identifiées comme les plus importantes pour la refactorisation.

## Refactorisation

### Modèle

Si on se base sur le modèle existant des règles (`StaplingRule`), on identifie les champs suivants qui peuvent être refactorisés : `glueOperator`, `comparisonOperator`, `value`, `metadata`. Ils participent d'une logique de base pour construire les différents éléments d'une `Query`.

On les remonte donc dans une classe parente, `AbstractRule`, puis on laisse l'ouverture à l'extension avec `StaplingRule`, et éventuellement `SignRule` (et à l'avenir, `ClassifyRule`, `ProtectRule` pour ajouter un mot de passe au document suivant certains critères ? etc...).

Dans une classe enfant, comme `SignRule` par exemple, on a donc le loisir de définir des propriétés supplémentaires (par exemple `optional` comme donné en exemple dans le brief).

L'attribut `MappedSuperClass` sur la classe abstraite permet de profiter de l'héritage sans créer de table parente dans la base de données.

Enfin, on définit un trait `EntityIdTrait` qu'on réutilise dans les entités ayant un ID.

Dans la migration générée, le résultat est ce que je voulais : deux tables `sign_config` et `sign_rule`, sans toucher à l'existant.

### Services

Pour continuer le processus de refactorisation, on va vouloir que la génération de requêtes fonctionne également pour nos configurations `SignConfig` accompagnées de leurs règles `SignRule`.

Le service `StaplingConfigQueryGenerator` est actuellement dédié au traitement des configurations `StaplingConfig` et des règles associées. Le but est de réduire voire supprimer le couplage présent avec `StaplingXxxx`.

On crée donc une classe abstraite `AbstractConfigQueryGenerator` de laquelle hériteront deux services concrets : `StaplingConfigQueryGenerator` et `SignConfigQueryGenerator`. Les méthodes privées deviennent protégées.

Dans `SignConfigQueryGenerator` on surcharge alors la méthode `processRule` : si la règle est optionnelle, alors on retourne directement, pas besoin de l'ajouter à la requête finale.

J'ai rajouté une classe de tests pour les règles optionnelles et la classe `SignConfigQueryGenerator`.

### Contrôleurs, formulaires & vues

Au niveau des formulaires, on définit une classe de base `AbstractRuleType` qui reprend les champs de base, à présent communs à `Stapling` et `Sign`.

Pour les contrôleurs, on utilise `make:crud`, qui semble avoir été utilisé initialement pour le `StaplingConfigController`, et on applique la même logique et les mêmes templates. La grosse différence, bien sûr, c'est l'utilisation du service `SignConfigQueryGenerator` à la place de `StaplingConfigQueryGenerator`.

## Observations finales

Si vous le souhaitez, on peut discuter de tous ces points en détails.

Il était parfois compliqué d'arbitrer des choix en ne sachant pas trop quel style ou quel niveau d'abstraction vous attendiez. Je n'ai pas voulu demander trop de choses et je ne voulais pas me retrouver à passer finalement beaucoup trop de temps sur le test. Par exemple, la partie contrôleurs et vues sont les deux parties où je me suis dit que ce serait inutile de refactoriser, ou au moins, largement prématuré.
