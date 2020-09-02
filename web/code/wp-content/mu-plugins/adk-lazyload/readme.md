#Plugin ADK Lazy Load
Ajax load more plugin by ADK

Mode of use

To use the load more functionality use the shortcode
[adk-lazyload]

Properties:
    ° id
        [adk-lazyload id='my_id']
        Use id property when you have more than one plugin in the same page

    ° results_container_id
        [adk-lazyload results_container_id='my_container_id']
        This Attribute is required *
        The ID of the container where the results will be display

    ° load_scroll
        [adk-lazyload load_scroll='true']
        Use load_scroll property to activate or deactivate the load function when use scroll.
        This property automatically inactivate when there is more than once plugin in the same page

    ° load_button
        [adk-lazyload load_button='true']
        Use load_button property to activate or deactivate the load function through button click action

    ° load_more_txt
        [adk-lazyload load_more_txt='My Load More Text' load_button='true']
        Use load_more_txt property to load more button display text.
        The load_button property has to be activated

    ° load_pagination
        [adk-lazyload load_pagination='true']
        Use load_pagination property to show "Preview" and "Next" buttons and use classic pagination.
        activate this one property deactivate load_scroll and load_button properties

    ° pagination_url
        [adk-lazyload pagination_url='true' load_pagination='true']
        Allows modify the pagination via URL
        Example: 
            http://localhost/3 will display the page 3
        The load_pagination property has to be activated

    ° Callback function before send
        [adk-lazyload cf_before='MyCallbackFunctionName']
        Client function to execute before the petition been sent
        IMPORTANT: The callback function should be defined as global
        Example:
            window.MyCallbackFunctionName = function () {
                console.log('This is the function Callback');
            };

    ° Callback function after complete
        [adk-lazyload cf_after='MyCallbackFunctionName']
        Client function to execute after complete the petition
        IMPORTANT: The callback function should be defined as global
        Example:
            window.MyCallbackFunctionName = function () {
                console.log('This is the function Callback');
            };
    
    ° year
        [adk-lazyload year='2019']
        Use year property to filter the response by the posted year
    
    ° month
        [adk-lazyload month='2019']
        Use month property to filter the response by the posted month

    ° day
        [adk-lazyload day='2019']
        Use day property to filter the response by the posted day

    ° posts_per_page
        [adk-lazyload posts_per_page='2']
        Use posts_per_page property to indicate the number of results to display per load or page

    ° posts_first_page
        [adk-lazyload posts_first_page='3']
        Use posts_first_page property to indicate the number of results to display in the first load or page

    ° Autoload
        [adk-lazyload posts_first_page='3']
        Search for the first page when the page is load. Use false for deactive the first load

    ° post_type
        [adk-lazyload post_type='post,page']
        Use post_type property to filter the post-type search
        Allows multiple values separated by comma (,)

    ° post_status
        [adk-lazyload post_status='publish,pending']
        Use post_status property to filter the post_status search
        Allows multiple values separated by comma (,)
    
    ° tag
        [adk-lazyload tag='tag']
        Use tag property to filter by tags
        Allows multiple values separated by comma (,)

    ° post_in
        [adk-lazyload post_in='1,2,3']
        Filter by the post IDs.
        Allows multiple values separated by comma (,)
    
    ° post_not_in
        [adk-lazyload post_not_in='1,2,3']
        Filter excluding the post IDs.
        Allows multiple values separated by comma (,)

    
    ° tax_query
        [adk-lazyload tax_query='Taxonomy|field|terms|operator']
        Use tax_query property to filter the taxonomy search
        Allows multiple taxonomies separated with semicolon (;)

        A taxonomy is composed by four parts, separated with pipe (|)
        Taxonomy: Query by custom taxonomy name
        Field: Select taxonomy term by
        Terms: list of custom taxonomy terms to query. Allows multiple values separated by comma (,)
        Operator: Compare taxonomy terms against. (IN / NOT IN)
        
        Example:
            [adk-lazyload tax_query='tags|slug|some-tag|AND ; category|slug|books,author|IN']

    ° tax_relation
        [adk-lazyload tax_relation='AND']
        Use tax_relation property to define the logical relationship between two taxonomies (AND / OR)

    ° meta_query
        [adk-lazyload meta_query='Key|value|compare|type']
        Use meta_query property to filter by custom fields
        Allows multiple custom queries separated with semicolon (;)

        A custom query is composed by three parts, separated with pipe (|)
        Key: The custom field name
        Value: Select value term by. 
            Allows multiple values separated by comma (,) but according to WP_Meta_Query documentation: only when compare is 'IN', 'NOT IN', 'BETWEEN', or 'NOT BETWEEN'
        Compare: Operator
        Type: (Optional) Custom field type. Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'
        
        Example:
            [adk-lazyload meta_query='location|Melbourne|LIKE ; location|Sydney,Berlin|=']

    ° meta_relation
        [adk-lazyload meta_relation='AND']
        Use meta_relation property to define the logical relationship between two custom fields queries(AND / OR)

    ° orderby
        [adk-lazyload orderby='Field|Order type']
        Sort retrieved posts
        A custom query is composed by four parts, separated with pipe (|)

        Field: the field to sort by
        Order type: Asc or Desc

        Example:
            [adk-lazyload orderby='title|ASC,date|DESC']

    ° order_meta_key
        [adk-lazyload order_meta_key='Custom Field']
        Sort posts ordered by 'meta_key' custom field

    ° template
        [adk-lazyload template='TEMPLATE_NAME']
        Use template property to define the template name that will be used to display the results.
        This file should be created inside the Theme folder. And this file should be named as follow:
        adkll_content-TEMPLATE_NAME.php

    ° no_results
        [adk-lazyload no_results='TEMPLATE_NAME']
        Use no_results property to define the template name that will be used to display the No Results message.
        This file should be created inside the Theme folder. And this file should be named as follow:
        adkll_content-TEMPLATE_NAME.php

    ° class_block
        [adk-lazyload class_block='MY_CUSTOM_CLASSNAME']
        Use class_block property to replace the default class for all pagination container.
        
    ° class_item
        [adk-lazyload class_item='MY_CUSTOM_CLASSNAME']
        Use class_item property to replace the default class for every pagination item.
        
    ° class_link
        [adk-lazyload class_link='MY_CUSTOM_CLASSNAME']
        Use class_link property to replace the default class for every pagination link.
        
    ° class_active
        [adk-lazyload class_active='MY_CUSTOM_CLASSNAME']
        Use class_active property to replace the default class for the active link or current page link.
        
    ° class_disabled
        [adk-lazyload class_disabled='MY_CUSTOM_CLASSNAME']
        Use class_disabled property to replace the default class for the disabled links.
    

    ° adkll_filter(objMyFilter)
    Javascript function to filter the dynamically
    Param: Javascript Object with the optionals properties
        id: If id is setted, the filter only apply to the shortcode with the correspond id
        query: A classic filter by terms
        post_type: Add or modify the post_type property
        tax_query: Add or modify the tax_query property
        tax_relation: Add or modify the tax_relation property
        meta_query: Add or modify the meta_query property
        meta_relation: Add or modify the meta_relation property
        year: Add or modify the year property
        month: Add or modify the month property
        day: Add or modify the day property
        post_in: Add or modify the post_in property
        post_not_in: Add or modify the post_not_in property
        post_title: Add or modify the post_title property
        post_title_like: Add or modify the post_title_like property
        tag: Add or modify the tag property

    Use Example: 

    // ON CLICK BUTTON THEN
    var objMyFilter = {
        id: "my_shortcode_id",
        query: "lorem",
        post_type : "post,page,books",
        tax_query : "category|slug|books,author|IN;tags|slug|some-tag|AND",
        tax_relation: "AND"
        meta_query : "key|value|compare;key|value|compare",
        meta_relation: "AND"
        year: "2019",
        month: "4",
        day: "5",
        post_in: "1,2,3",
        post_not_in: "4,5,6",
        post_title: "Exact title",
        post_title_like: "In Title",
        tag: "tag",
    }
    adkll_filter(objMyFilter); // OR window.adkll_filter(objMyFilter);

## Known Issues

This plugin might present issues with the next WordPress plugins

### W3 Total Cache

There is a reported issue where the page with the shortcode will keep reloading infinitely. To fix this you will need to set the **'Fragmented Cache'** to **'Disk'**