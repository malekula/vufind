curl http://192.168.3.135:8788/solr/biblio/update --data "<delete><query>*:*</query></delete>" -H "Content-type:text/xml; charset=utf-8"



curl http://192.168.3.135:8788/solr/biblio/update --data "<commit/>" -H "Content-type:text/xml; charset=utf-8"