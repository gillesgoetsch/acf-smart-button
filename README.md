# ACF Smart Button

A simple, clean and lean ACF Field that allows the user to select an internal link as a post_object or an external link as a url field via a smooth toggle.

It always returns the url as the same field, whether it's an internal or external link. With button.target you can additionally add target="_blank" in your template without additional casing.

![alt tag](https://cloud.githubusercontent.com/assets/2161918/11077731/e4106c2e-8801-11e5-8c71-ef265a428a3c.png)

![alt tag](https://cloud.githubusercontent.com/assets/2161918/11077733/e5643a06-8801-11e5-93f2-b99aba00e971.png)

Example (twig style):  

```
{% if button %}   
  <a href="{{ button.url }}" {{button.target }}>{{ button.text }}</a>   
{% endif %}
```

Isn't that lean =)?

Example (vanilla PHP):  

```
if ( get_field( 'acf_button_field' ) ) :
  $button = get_field( 'acf_button_field' );
  $button_label = $button['text'];
  $button_url = $button['url'];
  $button_target = $button['target'];
endif;
```

## Output / Return

### When Internal
[text] => I am an internal button   
[url] => http://yoursite.dev/selected-page  
[target] => ''

### When External
[text] => I am an external button  
[url] => http://kollektiv.ag  
[target] => 'target="blank"'

**Note:** In both cases the field data will only be returned if the button text is set in combination with a target. If either one is missing, there will be no data returned.

## Future Plans
- ~~option to select allowed post_types for post_object selector~~ 7626ad4
- language support
- validate url field
- cleanups etc.
- ~~eventually acf4 support~~ (no?)

## Compatibility

This add-on works only with version 5 and up.

## Installation

This add-on can be treated as both a WP plugin and a theme include.

**Install as Plugin**

1. Copy the 'acf-button' folder into your plugins folder
2. Activate the plugin via the Plugins admin page

**Include within theme**

1.	Copy the 'acf-smart-button' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-button.php file)

```php
include_once('acf-smart-button/acf-smart-button.php');
```

## Else

Partially inspired by https://github.com/envex/acf-button-field, but is missing acf5 support and didn't meet my requirements fully.
