# Models

Les models sont des *wraps* pour des données, ce sont des maps associatives. On préfère leur usage aux très commodes `array` car il est plus facile de savoir quelles clefs sont attendues sur ces objets en inspectant la classe qu'avec des tableaux.

Toutes ces classes **doivent être passées en `read-only`**. Je ne le fais pas car je n'ai pas encore d'analyseur de code PHP8.* compatible et d'avoir des fausses erreurs affichées sous les yeux en permanence me fatigue...

[PHP8.2](https://php.watch/versions/8.2) a ajouté une *syntaxic sugar* avec un mot clef `readonly` applicable directement sur le nom de la classe pour rendre toutes ses propriétés `readonly`.