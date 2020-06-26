# chatbot
Plugin chatbot (neareo) for Magix CMS 3

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu pour installer recaptcha.
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
* Copier le contenu du dossier **skin/public** dans le dossier de votre skin.

### Ajouter dans head du layout.tpl la ligne suivante :
```smarty
{widget_chatbot
        get=$smarty.get
        allowedSections=[
            'home' => 'root',
            'about' => 'root',
            'contact' => 'root',
            'pages' => [1,4],
            'news' => 'item'
        ]
        allowedWs=[
            'home',
            'about',
            'pages',
            'product',
            'category',
            'catalog'
        ]
    }
````
```html
<link rel="preconnect" href="https://neareo.com"/>
<link rel="dns-prefetch" href="https://neareo.com"/>
````

### Ajouter avant la fermeture du body du layout.tpl la ligne suivante :
```smarty
<script src="https://neareo.com/js/bot.js?key=mykey{if $neareoScriptWithSubkey}-mysubkey{/if}" {if $neareoVar != NULL && $neareoScriptWithSubkey}data-pgid="{$neareoVar}"{/if} type="text/javascript" defer></script>
````

## exemple d'URL :
```text
http://www.domain.tld/chatbot?collection=home
http://www.domain.tld/chatbot?collection=pages&id=1
````