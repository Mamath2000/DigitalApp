
GET     /api/{class}?name=..&idAssociateStatus=..&dependency={1|0}          
GET     /api/{class}/{idx}?{dependency={1|0}}
PUT     /api/{class}/{idx}      JSON:{....object....}
POST    /api/{class}            JSON:{....object....}
DELETE  /api/{class}/{idx}


GET     /api/associates/{idx}/timelines?idReports={idx}
GET     /api/associates/{idx}/reports
GET     /api/associates/{idx}/cells?idReports={idx}&year={0000}
PUT     /api/associates/{id}/cells      JSON:{cells: [ .... ]}
PUT     /api/associates/{id}/reportcalc JSON:{idReports:'idx',year:'0000'}
PUT     /api/associates/{id}/rowcalc    JSON:{idLinesDef:'idx',year:'0000'}

PUT     /api/users/check
PUT     /api/users/{idx}/password   JSON:{password: ....}

PUT     /api/login                  JSON:{"email":'email',"password":'xxxxx'}

POST    /api/locks      JSON:{idReport:'idx',idAssocaites:'idx',year:'0000'}
PUT     /api/locks                  JSON:{idReports:'idx',idAssociates:'idx',year:'0000',isLock:[1|0]}