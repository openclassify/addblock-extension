# **Add Block Module**

This module provides a powerful hook-based system that allows you to dynamically inject content from various modules into specified areas of your application's Twig templates. It is designed to enhance modularity, allowing different parts of the system to extend and interact with each other without creating tight dependencies. This approach is fundamental for building scalable and maintainable applications where features can be added or removed easily.

## **Core Concept**

The primary goal of the `addBlock` function is to create designated "slots" within your views. Other modules can then "fill" these slots with their own content by providing a Twig file that matches the slot's name. When a view is rendered, the system searches all active modules for corresponding Twig files and injects their rendered HTML into the specified slot. This decouples the module that defines the slot from the modules that provide the content.

## **Blocks**

Here are some practical examples of blocks used throughout the system. Each block represents a point of extension within a view.

**advs-module/ad-detail/partials/author-button**:

 This block is typically located near the author's information on an ad detail page. It can be used by other modules to add buttons, such as "Follow User" or "View Store".  

{{ addBlock('ad-detail/partials/author-button', {'adv\_id': adv.id})|raw }}


**advs-module/list/partials/ads**: 

This block is used within an ad listing. A module could use this to inject a special banner or a featured ad into the list.  


{{ addBlock('ad-list/partials/ads', {'featured\_advs': featured\_advs})|raw }}


**profile-module/profile/partials/navigation**:

 Extends the user's profile navigation menu. Modules can add new links here, for example, a "My Invoices" link from a billing module.  

{{ addBlock('profile/navigation')|raw }}


**advs-module/ad-detail/partials/detail**: 

A general-purpose block within the main detail section of an ad. It's ideal for adding significant chunks of information, like a shipping calculator or a list of similar ads.  

{{ addBlock('ad-detail/seller/action', {'adv': adv})|raw }}


**advs-module/ad-detail/detail**: 

This block is placed right after the ad title. It can be used to add icons, labels (e.g., "Verified"), or actions related to the ad itself.  

{{ addBlock('ad-detail/title/action', {'adv': adv})|raw }}


**advs-module/new-ad/new-create**:
 This allows modules to add custom fields to the "Create New Ad" form. For instance, a vehicle module could inject fields for "Make," "Model," and "Year."  

{{ addBlock('new-ad/fields')|raw }}


# **Usage**

The function is called within any Twig file where you want to create an extension point.

### **Function Signature**

{{ addBlock('view\_path\_identifier', {key1: value1, key2: value2, ...})|raw }}

* **`view_path_identifier` (string)**: 

This is the unique name for your block. The system will search for a Twig file with this name (e.g., `view_path_identifier.twig`) in the `resources/views` directory of all active modules.  
* `{...}` **(associative array, optional)**: 

This is a data array that passes variables from the parent view to the injected Twig file. This allows the injected content to be dynamic and context-aware. For example, passing the `adv` object gives the block full access to the ad's details.  
* **`|raw` (Twig filter)**:

 This filter is crucial. It ensures that the HTML rendered by the injected Twig file is not auto-escaped by Twig. Without it, you would see HTML tags as plain text on the screen.

### **Example**

Let's expand on the example of adding a contact button from the **Message Module** to the **Advs Module's** detail page.

**File:** `advs-module/resources/views/ad-detail/partials/detail.twig` **Lines:** 51-63

``` 
{% set contactWith \= addBlock('ad-detail/contact-with', {'adv': adv}) %}  
{% if contactWith|trim is not empty %}  
    \<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 offered-field offered-row"\>  
        \<div class="row"\>  
            \<div class="col-md-12 mt-2"\>  
                \<h4\>\<u\>{{ trans('visiosoft.module.advs::field.contact\_with') }}:\</u\>\</h4\>  
            \</div\>  
            {{ contactWith|raw }}  
        \</div\>  
    \</div\>  
{% endif %}   
```

In the code above, `addBlock('ad-detail/contact-with', ...)` searches for the file `"ad-detail/contact-with.twig"` inside the `views` folder of all other installed modules. It then automatically renders the content of any found Twig files in the location where `addBlock` was called.

**How it works behind the scenes:**

1. The ad detail page renders and reaches the `addBlock` call.  
2. The system scans all active modules (e.g., `profile-module`, `message-module`, `location-module`).  
3. It finds `resources/views/ad-detail/contact-with.twig` in the **Profile Module**. This file might contain code to display the seller's phone number.  
4. It also finds `resources/views/ad-detail/contact-with.twig` in the **Message Module**. This file might contain a "Send Private Message" button.  
5. The system renders the content of both files, passing the `adv` object to each.  
6. The final HTML from both modules is combined and injected into the `contactWith` variable. The `if` condition ensures the section is only displayed if at least one module returned content.

**Example of an included Twig file:**

`profile-module/resources/views/ad-detail-contact-with.twig`

In this example from the Profile module, the user's phone information is injected into the ad detail page.
