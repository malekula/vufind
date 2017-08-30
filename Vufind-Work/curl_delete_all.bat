curl http://192.168.56.31:8080/solr/biblio/update --data "<delete><query>*:*</query></delete>" -H "Content-type:text/xml; charset=utf-8"



curl http://192.168.56.31:8080/solr/biblio/update --data "<commit/>" -H "Content-type:text/xml; charset=utf-8"