var el=wp.element.createElement,aphphIcon=wp.element.createElement("svg",{"aria-hidden":"true",role:"img",focusable:"false",viewBox:"0 0 512 512",width:"24",height:"24"},wp.element.createElement("path",{d:"M64 416l192-320 192 320z"}));function encodeEntities(t){return encoded=jQuery("<textarea>").html(t).html(),encoded}function getAdditionalCSS(t){if(additionalCSS="",t){for(k in list_class=t.split(" "),addCss=[],list_class)"wp-block-aphph-prism-block"!=list_class[k]&&list_class[k].indexOf("language")<0&&list_class[k].indexOf("lang")<0&&addCss.push(list_class[k]);addCss.length>0&&(additionalCSS=" "+addCss.join(" "))}return additionalCSS}wp.blocks.registerBlockType("aphph/prism-block",{title:"APH Prism",icon:aphphIcon,category:"common",attributes:{language:{type:"string",default:aphph_default_lang},content:{type:"string",source:"text",selector:"code"},attrClass:{type:"string",source:"attribute",selector:"pre",attribute:"class"},highlightLineNumber:{type:"string",source:"attribute",selector:"pre",attribute:"data-line",default:""},startingLineNumber:{type:"string",source:"attribute",selector:"pre",attribute:"data-start",default:""}},edit:function(t){if(t.attributes.attrClass)for(k in t.attributes.additionalCSS=getAdditionalCSS(t.attributes.attrClass).trim(),list_class=t.attributes.attrClass.split(" "),list_class)list_class[k].indexOf(":")>0&&(split_data=list_class[k].split(":"),selected_lang=jQuery.trim(split_data[1]),t.attributes.language=jQuery.trim(split_data[1]));return t.attributes.attrClass="",selected_lang=t.attributes.language,additionalCSS=t.attributes.additionalCSS,$element=el("div",{className:"aphph-block-wrapper"},el("select",{onChange:function(e){t.setAttributes({language:e.target.value})},"data-lang":selected_lang,value:selected_lang},function(){for(k in lang_list=JSON.parse(aphph_lang_list),option=[],lang_list)val_selected=k==aphph_default_lang?"SELECTED":"",option.push(el("option",{value:k,selected:!0},lang_list[k]));return option}()),el("textarea",{placeholder:"Enter code here...",rows:10,className:"aphph-block-textarea",onChange:function(e){t.setAttributes({content:e.target.value})},style:{width:"100%"}},t.attributes.content),el("input",{help:"Highlight line number",type:"text",placeholder:"Highlight line number",onChange:function(e){t.setAttributes({highlightLineNumber:e.target.value})},value:t.attributes.highlightLineNumber}),el("input",{label:"Start line number",type:"text",placeholder:"Starting line number",value:t.attributes.startingLineNumber,onChange:function(e){t.setAttributes({startingLineNumber:e.target.value})}}),el("input",{label:"Additional CSS",type:"text",placeholder:"Additional CSS",value:additionalCSS,onChange:function(e){t.setAttributes({additionalCSS:e.target.value})}})),$element},save:function(t){return addCss="",t.attributes.attrClass&&(addCss=getAdditionalCSS(t.attributes.attrClass)),t.attributes.additionalCSS&&(addCss=" "+t.attributes.additionalCSS),attr={className:"lang:"+t.attributes.language+" language-"+t.attributes.language+addCss},t.attributes.highlightLineNumber&&(attr["data-line"]=t.attributes.highlightLineNumber),t.attributes.startingLineNumber&&(attr["data-start"]=t.attributes.startingLineNumber),el("pre",attr,el("code",null,encodeEntities(t.attributes.content)))}}),wp.domReady(function(){$select=jQuery(".aphph-block-wrapper").find("select"),$elm=document.querySelectorAll(".aphph-block-wrapper"),$select.each(function(t,e){$elm=jQuery(e),lang=$elm.attr("data-lang"),$elm.find('option[value="'+lang+'"]').prop("selected",!0),$elm.find('option[value="'+lang+'"]').attr("selected","selected")}),jQuery(document).delegate(".aphph-block-textarea","keydown",tabOverride.handlers.keydown),jQuery(document).delegate(".aphph-block-textarea","keypress",tabOverride.handlers.keypress)});