var imgdir = 'modules/SDK/examples/uitypeSocial/img/';
getObj(dtlView).innerHTML = "<img src=\""+resourcever(imgdir+"bkico.png")+"\" align=\"left\" alt=\"VKontakte\" title=\"VKontakte\"/>";
if (tagValue != '') {
  getObj(dtlView).innerHTML += "<a target=\"_blank\" href=\"http://vkontakte.ru/"+tagValue+"\">http://vkontakte.ru/"+tagValue+"</a>";
}
