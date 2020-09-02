<?php
    if (! current_user_can ('manage_options')) wp_die (__ ('You do not have permission to access this page'));
?>

<div id="adkll" class="wrap">
    <h1>Lazy Load by ADK</h1>

    <h2>Shortcode attributes configuration</h2>
    <br>
    <fieldset>
    <legend>Select the desired configuration and then copy the formed shortcode below</legend>
        <div>
            <table>
                <tr>
                    <td>
                        <h2>Display query</h2>
                        <p>
                            Use this attribute if you wish check how the Wp_Query is formed
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>display_query</b> | Default:</i> false</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="display_query"/></td>
                </tr>
                <tr>
                  <td>
                    <h2>ElasticPress Integration</h2>
                    <p>
                      Activate if you want to use elasticPress in this query
                    </p>
                    <div class='adkll_admin_default'><i>Attribute: <b>ep_integrate</b> | Default:</i> false</div>
                    <hr>
                  </td>
                  <td><input type="checkbox" value="ep_integrate"/></td>
                </tr>
                <tr>
                    <td>
                        <h2>Container ID</h2>
                        <p>
                            Use an id attribute when you have more than one shortcode in the same page
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>id</b> | Default:</i> defId</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="id"/></td>
                    <td><input type="text" name="id" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Results Container ID *</h2>
                        <p>
                            The ID of the container where the results will be display<br>
                            This Attribute is required *
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>results_container_id</b> | Default:</i> ''</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="results_container_id" checked="checked"/></td>
                    <td><input type="text" name="results_container_id" autofocus="true" placeholder="*required"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Classic pagination</h2>
                        <p>
                            Show a <i>Preview</i> and <i>Next</i> controls for load more post with pagination
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>load_pagination</b> | Default:</i> false</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="load_pagination"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Pagination in URL</h2>
                        <p>
                            Allows modify the pagination via URL<br>
                            example: 
                            http://localhost/3 will display the page 3
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>pagination_url</b> | Default:</i> false</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="pagination_url"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Load on scroll</h2>
                        <p>
                            Activate the load more on page scroll
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>load_scroll</b> | Default:</i> true</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="load_scroll" checked="checked"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Load on click button</h2>
                        <p>
                            Activate the load more function on button click
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>load_button</b> | Default:</i> true</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="load_button" checked="checked"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Load more text</h2>
                        <p>
                            Load more button text<br>
                            load_button attribute should be active to display the button.
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>load_more_txt</b> | Default:</i> Load more...</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="load_more_txt"/></td>
                    <td><input type="text" name="load_more_txt" disabled="disabled"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Callback function before send</h2>
                        <p>
                            Client function to execute before the petition been sent
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>cf_before</b> | Default:</i></div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="cf_before"/></td>
                    <td><input type="text" name="cf_before" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Callback function after send</h2>
                        <p>
                            Client function to execute after complete the petition
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>cf_after</b> | Default:</i></div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="cf_after"/></td>
                    <td><input type="text" name="cf_after" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Number of posts per page</h2>
                        <p>
                            Set the number of posts to load per page
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>posts_per_page</b> | Default: wordpress pagination value</i></div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="posts_per_page"/></td>
                    <td><input type="number" min="1" name="posts_per_page" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Initial number of posts</h2>
                        <p>
                            Set the number of posts to show in the initial load
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>posts_first_page</b> | Default: posts_per_page attribute</i></div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="posts_first_page"/></td>
                    <td><input type="number" min="1" name="posts_first_page" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Autoload</h2>
                        <p>
                            Search for the first page when the page is load
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>autoload</b> | Default: true</i></div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="autoload" checked="checked"/></td>
                </tr>

                <tr>
                
                  <td>
                    <h2>Custom Fields</h2>
                    <p>
                      Select this option if shortcode is going to be used inside<br>
                      a Custom Field<br>
                      <i><b>Example:</b> repeater, relationship, image gallery</i>
                    </p>
                    <div class='adkll_admin_default'><i>Attribute: <b>Custom Field Name</b> | Default:</i> None </div>
                    <hr>
                  </td>
                  <td><input type="checkbox" value="acf_name"/></td>
                  <td><input type="text" name="acf_name" disabled="disabled"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Post type</h2>
                        <p>
                            Set the post type to load results from<br>
                            Allows multiple values: separated by comma (,)<br>
                            <b>Possible values:</b><br>
                            post, page, revision, attachment, nav_menu_item, any<br>
                            or custom post type<br><br>
                            <i><b>Example:</b> post,page,books</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_type</b> | Default:</i> post</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_type"/></td>
                    <td><input type="text" name="post_type" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Post tags</h2>
                        <p>
                            Filter the search by the post tags<br>
                            Allows multiple values: separated by comma (,)<br>
                            <i><b>Example:</b> tag1,tag2</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>tag</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="tag"/></td>
                    <td><input type="text" name="tag" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Year</h2>
                        <p>
                            Filter the response by the posted year
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>year</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="year"/></td>
                    <td><input type="number" name="year" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Month</h2>
                        <p>
                            Filter the response by the posted month
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>month</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="month"/></td>
                    <td><input type="number" name="month" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Day</h2>
                        <p>
                            Filter the response by the posted day
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>day</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="day"/></td>
                    <td><input type="number" name="day" disabled="disabled"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Post status</h2>
                        <p>
                            The post status to search for.<br>
                            Allows multiple values: separated by comma (,)<br>
                            <b>Possible values:</b><br>
                            publish, pending, draft, auto-draft, future, private, inherit, trash, any<br><br>
                            <i><b>Example:</b> publish,pending</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_status</b> | Default:</i> publish</div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_status"/></td>
                    <td><input type="text" name="post_status" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Post In</h2>
                        <p>
                            Filter by the post ID<br>
                            Allows multiple values: separated by comma (,)<br>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_in</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_in"/></td>
                    <td><input type="text" name="post_in" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Post Not In</h2>
                        <p>
                            Filter by the post ID<br>
                            Allows multiple values: separated by comma (,)<br>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_not_in</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_not_in"/></td>
                    <td><input type="text" name="post_not_in" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Post title</h2>
                        <p>
                            The exact post title to search for
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_title</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_title"/></td>
                    <td><input type="text" name="post_title" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Post title like</h2>
                        <p>
                            Words in the post title to search for
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>post_title_like</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="post_title_like"/></td>
                    <td><input type="text" name="post_title_like" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Taxonomies</h2>
                        <p>
                            Filter for one or more taxomomies<br>
                            A taxonomy is composed by four parts, separated with pipe (|)<br><br>

                            Taxonomy | field | terms | operator<br><br>
                            
                            Terms: Allows multiple values separated by comma (,)<br>
                            Taxonomies: Allows multiple values separated with semicolon (;)<br><br>
                            
                            <i><b>Example:</b><br>category</i><b>|</b><i>slug</i><b>|</b><i>books,author<b>|</b><i>IN<b> ; </b>tags</i><b>|</b><i>slug</i><b>|</b><i>some-tag,other</i><b>|</b><i>AND</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>tax_query</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="tax_query"/></td>
                    <td><input type="text" name="tax_query" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Taxonomy relation</h2>
                        <p>
                            The logical relationship between each taxonomy when there is more than one<br>
                            <b>Possible values:</b><br>
                            AND, OR<br><br>

                            <i><b>Example:</b> OR</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>tax_relation</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="tax_relation"/></td>
                    <td><input type="text" name="tax_relation" disabled="disabled"/></td>
                </tr>
                
                <tr>
                    <td>
                        <h2>Custom fields query</h2>
                        <p>
                            Filter for one or more Custom fields<br>
                            A custom query is composed by four parts, separated with pipe (|)<br><br>

                            Key | value | compare | type<br><br>
                            
                            value: Allows multiple values separated by comma (,)<br>
                            Type: (Optional) Custom field type. Possible values are 'NUMERIC', 'BINARY', 'CHAR', 'DATE', 'DATETIME', 'DECIMAL', 'SIGNED', 'TIME', 'UNSIGNED'<br>
                            Custom queries: Allows multiple values separated with semicolon (;)<br><br>
                            
                            <i><b>Example:</b><br>initial_date</i><b>|</b><i>2019-01-01</i><b>|</b><i>><b>|</b><i>DATE<b> ; </b>location</i><b>|</b><i>Sydney,Berlin</i><b>|</b><i>=</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>meta_query</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="meta_query"/></td>
                    <td><input type="text" name="meta_query" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Custom fields query relation</h2>
                        <p>
                            The logical relationship between each Custom field query when there is more than one<br>
                            <b>Possible values:</b><br>
                            AND, OR<br><br>
                            
                            <i><b>Example:</b> OR</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>meta_relation</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="meta_relation"/></td>
                    <td><input type="text" name="meta_relation" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Order By</h2>
                        <p>
                            Sort retrieved posts<br>
                            A custom query is composed by four parts, separated with pipe (|)<br><br>

                            Field | Order type (Asc or Desc)<br><br>

                            value: Allows multiple values separated by comma (,)<br>
                            
                            <i><b>Example:</b><br>title</i><b>|</b><i>ASC</i><b> , </b>date</i><b>|</b><i>Desc</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>orderby</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="orderby"/></td>
                    <td><input type="text" name="orderby" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Order meta_key</h2>
                        <p>
                            Sort posts ordered by 'meta_key' custom field:<br>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>order_meta_key</b> | Default:</i> </div>
                        <hr>
                    </td>
                    <td><input type="checkbox" value="order_meta_key"/></td>
                    <td><input type="text" name="order_meta_key" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Template</h2>
                        <p>
                            The template name that will be used to display the results.<br>
                            This file should be created inside the <b>Theme</b> folder<br>
                            and should be named as follow:<br>
                            <i>adkll_content-<b>NAME</b>.php</i><br>
                            For example: adkll_content-basic.php<br>
                            If the defined name is basic.
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>template</b> | Default: </i></div>
                    </td>
                    <td><input type="checkbox" value="template"/></td>
                    <td><input type="text" name="template" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>No Results template</h2>
                        <p>
                            The template name that will be used to display the No Results message.<br>
                            This file should be created inside the <b>Theme</b> folder<br>
                            and should be named as follow:<br>
                            <i>adkll_content-<b>NAME</b>.php</i><br>
                            For example: adkll_custom-no-results.php<br>
                            If the defined name is basic.
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>no_results</b> | Default: </i></div>
                    </td>
                    <td><input type="checkbox" value="no_results"/></td>
                    <td><input type="text" name="no_results" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <hr>
                        <h2>Classic pagination styles</h2>
                        <p>
                            Use the follows attributes to set a class to the different pagination elements.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h2>Class pagination block</h2>
                        <p>
                            Class for all pagination container.
                            You can use your own class ussing the <i>class_block</i> attribute.<br>
                            Or you can use the default class <i>.pag_block</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>class_block</b> | Default: pag_block</i></div>
                    </td>
                    <td><input type="checkbox" value="class_block"/></td>
                    <td><input type="text" name="class_block" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Class pagination item</h2>
                        <p>
                            Class for every pagination item.
                            You can use your own class ussing the <i>class_item</i> attribute.<br>
                            Or you can use the default class <i>.pag_item</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>class_item</b> | Default: pag_item</i></div>
                    </td>
                    <td><input type="checkbox" value="class_item"/></td>
                    <td><input type="text" name="class_item" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Class pagination link</h2>
                        <p>
                            Class for every pagination link.
                            You can use your own class ussing the <i>class_link</i> attribute.<br>
                            Or you can use the default class <i>.pag_link</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>class_link</b> | Default: pag_link</i></div>
                    </td>
                    <td><input type="checkbox" value="class_link"/></td>
                    <td><input type="text" name="class_link" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Class pagination active</h2>
                        <p>
                            Class for the active link or current page link.
                            You can use your own class ussing the <i>class_active</i> attribute.<br>
                            Or you can use the default class <i>.pag_active</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>class_active</b> | Default: pag_active</i></div>
                    </td>
                    <td><input type="checkbox" value="class_active"/></td>
                    <td><input type="text" name="class_active" disabled="disabled"/></td>
                </tr>

                <tr>
                    <td>
                        <h2>Class pagination disabled</h2>
                        <p>
                            Class for the disabled links.
                            You can use your own class ussing the <i>class_disabled</i> attribute.<br>
                            Or you can use the default class <i>.pag_disabled</i>
                        </p>
                        <div class='adkll_admin_default'><i>Attribute: <b>class_disabled</b> | Default: pag_disabled</i></div>
                    </td>
                    <td><input type="checkbox" value="class_disabled"/></td>
                    <td><input type="text" name="class_disabled" disabled="disabled"/></td>
                </tr>
            </table>
        </div>
    </fieldset>
    <hr>
    <h4>Copy and paste the following shortcode in the template or editor where you want to show the results</h4>
    <div>
        <textarea id="adkll_output_shortcode" readonly="readonly">[adk-lazyload]</textarea>
    </div>
</div>