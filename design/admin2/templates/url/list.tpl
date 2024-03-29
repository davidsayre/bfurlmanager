{let item_type=ezpreference( 'admin_urlm_list_limit' )
     number_of_items=min( $item_type, 4)|choose( 10, 10, 25, 50, 100 )}

{let sort_type=ezpreference( 'admin_urlm_list_sort' )
 sort_by_option=min( $sort_type, 2)|choose( 'modified', 'modified','url' )}

{if is_set($filter_by)|not()} {def $filter_by = ''} {/if}

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

{switch match=$view_mode}
{case match='valid'}
    <h1 class="context-title">{'Valid links (%url_list_count)'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h1>
{/case}

{case match='invalid'}
    <h1 class="context-title">{'Invalid links (%url_list_count)'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h1>
{/case}

{case}
    <h1 class="context-title">{'All links (%url_list_count)'|i18n( 'design/admin/url/list',, hash( '%url_list_count', $url_list_count) )}</h1>
{/case}
{/switch}

{* DESIGN: Mainline *}<div class="header-mainline"></div>


{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="button-left">
    <p class="table-preferences">
    {switch match=$number_of_items}
       {case match=10}
        <span class="current">10</span>        
        <a href={'/user/preferences/set/admin_urlm_list_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/3'|ezurl}>50</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/4'|ezurl}>100</a>
        {/case}

    {case match=25}
        <a href={'/user/preferences/set/admin_urlm_list_limit/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_urlm_list_limit/3'|ezurl}>50</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/4'|ezurl}>100</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_urlm_list_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/2'|ezurl}>25</a>        
        <span class="current">50</span>
        <a href={'/user/preferences/set/admin_urlm_list_limit/4'|ezurl}>100</a>
        {/case}

       

        {case match=100}
        <a href={'/user/preferences/set/admin_urlm_list_limit/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/2'|ezurl}>25</a>                
        <a href={'/user/preferences/set/admin_urlm_list_limit/3'|ezurl}>50</a>
        <span class="current">100</span>
        {/case}


        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_urlm_list_limit/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/3'|ezurl}>50</a>
        <a href={'/user/preferences/set/admin_urlm_list_limit/4'|ezurl}>100</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="button-right">
<p class="table-preferences">
{switch match=$view_mode}
{case match='valid'}
<a href={'/urlm/list/all'|ezurl} title="{'Show all URLs.'|i18n( 'design/admin/url/list' )}">{'All'|i18n( 'design/admin/url/list' )}</a>
<span class="current">{'Valid'|i18n( 'design/admin/url/list' )}</span>
<a href={'/urlm/list/invalid'|ezurl} title="{'Show only invalid URLs.'|i18n( 'design/admin/url/list' )}">{'Invalid'|i18n( 'design/admin/url/list' )}</a>
{/case}

{case match='invalid'}
<a href={'/urlm/list/all'|ezurl} title="{'Show all URLs.'|i18n( 'design/admin/url/list' )}">{'All'|i18n( 'design/admin/url/list' )}</a>
<a href={'/urlm/list/valid'|ezurl} title="{'Show only valid URLs.'|i18n( 'design/admin/url/list' )}">{'Valid'|i18n( 'design/admin/url/list' )}</a>
<span class="current">{'Invalid'|i18n( 'design/admin/url/list' )}</span>
{/case}

{case}
<span class="current">{'All'|i18n( 'design/admin/url/list' )}</span>
<a href={'/urlm/list/valid'|ezurl} title="{'Show only valid URLs.'|i18n( 'design/admin/url/list' )}">{'Valid'|i18n( 'design/admin/url/list' )}</a>
<a href={'/urlm/list/invalid'|ezurl} title="{'Show only invalid URLs.'|i18n( 'design/admin/url/list' )}">{'Invalid'|i18n( 'design/admin/url/list' )}</a>
{/case}
{/switch}
</p>
</div>

<div class="float-break"></div>
</div>

<div class="block">
<form action="/urlm/list" method="post">
<label for="filterBy">Url Filter:</label>
<input type="text" name="filterBy" value="{$filter_by}"> <input type="submit" class="button" name="sbtn" value="Search">
</form>
</div>

{section show=$url_list}
<table class="list" cellspacing="0">

<tr>
  <th><a href={'/user/preferences/set/admin_urlm_list_sort/2'|ezurl} title="{'Sort by url.'|i18n( 'design/admin/url/list' )}" class="{if $sort_type|eq(2)}active{/if}">{'Url'|i18n( 'design/admin/url/list' )}</a></th>
  <th>{'Status'|i18n( 'design/admin/url/list' )}</th>
  <th>{'Checked'|i18n( 'design/admin/url/list' )}</th>
  <th><a href={'/user/preferences/set/admin_urlm_list_sort/1'|ezurl} title="{'Sort by modified.'|i18n( 'design/admin/url/list' )}" class="{if $sort_type|eq(1)}active{/if}">{'Modified'|i18n( 'design/admin/url/list' )}</a></th>
  <th class="tight">&nbsp;</th>
</tr>

{section var=urls loop=$url_list sequence=array( bglight, bgdark )}

<tr class="{$urls.sequence}">

  {* URL & popup. *}
  <td>{'url'|icon( 'small', 'URL'|i18n( 'design/admin/url/list' ) )}&nbsp;<a href={concat( 'url/view/', $urls.item.id)|ezurl} title="{'View information about URL.'|i18n( 'design/admin/url/list' )}">{$urls.item.url}</a>
  (<a href="{$urls.item.url}" target="_blank" title="{'Open URL in new window.'|i18n( 'design/admin/url/list' )}">{'open'|i18n( 'design/admin/url/list' )}</a>)
  </td>

  {* Status. *}
  <td>
  {if $urls.is_valid}
      {'Valid'|i18n( 'design/admin/url/list' )}
  {else}
      {'Invalid'|i18n( 'design/admin/url/list' )}
  {/if}
  </td>

  {* Last checked. *}
  <td>
    {if $urls.item.last_checked|gt( 0 )}
      {$urls.item.last_checked|l10n( shortdatetime )}
    {else}
        {'Never'|i18n( 'design/admin/url/list' )}
    {/if}
  </td>

  {* Last modified. *}
  <td>
    {if $urls.item.modified|gt( 0 )}
      {$urls.item.modified|l10n( shortdatetime )}
    {else}
      {'Unknown'|i18n( 'design/admin/url/list' )}
    {/if}
  </td>

  {* Edit. *}
  <td><a href={concat( 'url/edit/', $urls.item.id )|ezurl}><img src={'edit.gif'|ezimage} width="16" height="16" alt="{'Edit'|i18n( 'design/admin/url/list')}" title="{'Edit URL.'|i18n( 'design/admin/url/list' )}" /></a></td>

</tr>
{/section}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/urlm/list/', $view_mode )
         item_count=$url_list_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{section-else}
<div class="block">
<p>{'The requested list is empty.'|i18n( 'design/admin/url/list' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

</div>

{/let}
