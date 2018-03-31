Plugin pour commander les amplificateurs Marantz ou Denon récents. Vous pourrez contrôler
les entrées à utiliser, sortie de veille, mettre en veille
l’amplificateur et contrôler le volume, activer le mode sleep. 
Vous avez aussi un retour d’état
indiquant si la zone est active, le niveau de volume, l’entrée
selectionnée et le type audio.

Pour les amplis plus anciens, le plugin denonavr fonctionne toujours.

Configuration du plugin 
=======================

Après téléchargement du plugin, activer le plugin.

Configuration des équipements 
=============================

La configuration des équipements Marantz/Denon récents est accessible à partir du menu
Plugins puis multimedia.

Vous retrouvez ici toute la configuration de votre équipement :

-   **Nom de l’équipement Denon** : nom de votre équipement Denon,

-   **Objet parent** : indique l’objet parent auquel appartient
    l’équipement,

-   **Catégorie** : les catégories de l’équipement (il peut appartenir à
    plusieurs catégories),

-   **Activer** : permet de rendre votre équipement actif,

-   **Visible** : rend votre équipement visible sur le dashboard,

-   **IP** : IP de l’amplificateur denon

-   **Info Modèle** : La référence du modèle retourné par l'équipement (non modifiable)

-   **Type de Modèle** : Créer les commandes en fonction de ce choix

-   **Zone** : zone à contrôler (principale ou zone 2/3)

En dessous vous retrouvez la liste des commandes :

-   **Nom** : le nom affiché sur le dashboard,

-   **Type** : seulement type action est disponible,

-   **Commande brute** : la commande brute (voir spécifications),

-   **Paramêtres** : Afficher / historiser,

-   **Options** : options supplémentaires pour les actions,

-   **Action** : permet d’afficher la fenêtre de
    configuration avancée de la commande et tester la commande,


Il est possible d'ajouter des commandes supplémentaires en fonction des besoins. Les spécification en contient beaucoup est est disponible <a target="_blank" href="../assets/AVRX4000_PROTOCOL(10_3_0)_V03.pdf">en local</a> ou <a target="_blank" href="https://usa.denon.com/us/product/hometheater/receivers/avrx4000?docname=AVRX4000_PROTOCOL(10%203%200)_V03.pdf">en ligne</a>.
Pour cela la commande action devra avoir comme valeur ce qui se trouve dans le tableau des commandes disponibles de la doc (sans le <CR>). 


> **Note**
>
> Les commandes de bases sont générées automatiquement en fonction du modèle de
> votre amplificateur. Si le modèle n'est pas connu il prendra une configuration la plus étendue.
> 
> Le retour du volume n'a pas l'air de fonctionner sur tous les modèles (bug)

Changelog détaillé :
<https://github.com/guirem/plugin-marantzdenon/commits/stable>
