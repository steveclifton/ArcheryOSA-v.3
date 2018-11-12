# Event Results

## Method
`GET` <br>
`/api/event/{eventurl}/{competitionid = null}`
***

## Description
Returns results for an event.


***

## Parameters


**eventurl (Required)**
- The events URL Key (also see - **[upcoming events](https://github.com/steveclifton/ArcheryOSA-v.3/blob/master/api-documentation/GET_upcomingevents.md)**)

competitionid (Optional)
- If supplied, this particular event's competition results will be returned.
- If the eventype is a League (eventtypeid of 2), this param is used as the week number
- Otherwise, if left empty (or 'overall' is passed), the events overall results will be returned


***

## Return format
An array with the following keys and values:

- **success** — Bool - whether the request was successful or not
- **eventtype** — String - type of event (also see - **[upcoming events](https://github.com/steveclifton/ArcheryOSA-v.3/blob/master/api-documentation/GET_eventtypes.md)**)
- **data** — Array - container for the return data:
    - 'event' - Object of the events details
	- 'results' - Array of Arrays/Objects keyed by bowtypes and divisions

***
## Notes
1.  Users score results are keyed by `dist{num}` and `dist{num}score`
	- There might be more or less of these distance/score properties depending on the rounds shot

***


## Example 1
### Request - ***event with competitionid***
### Event Type - ***competition***
	Example URL
    https://www.archeryosa.com/api/event/2018-test-event/12

**Return** __shortened and ID content replaced for example purpose__
``` json
{
    "success": true,
    "data": {
        "event": {
            "eventid": 2,
            "eventtypeid": 1,
            "organisationid": 1,
            "clubid": 21,
            "entryclose": null,
            "start": "2018-05-26",
            "end": "2018-05-26",
            "daycount": 1,
            "contactname": "steve",
            "phone": "0211498154",
            "email": "info@archeryosa.com",
            "location": "Auckland Archery Club",
            "cost": "$30",
            "bankaccount": "01-1111-1111111-000",
            "bankreference": "Surname as reference",
            "schedule": "Start time: 9am",
            "info": "Prize giving at 5pm",
            "eventurl": "2018-test-event-2",
            "entrylimit": null,
            "eventname": "2018 Test Event"
        },
		"eventtype": "Competition",
        "results": {
            "compound": {
                "Women's Cadet Compound": [
	                {
		                "firstname": "test",
		                "lastname": "person",
		                "gender": "f",
		                "entrycompetitionid": 15,
		                "eventcompetitionid": 12,
		                "roundid": 5,
		                "divisionname": "Cadet Compound",
		                "bowtype": "compound",
		                "unit": "m",
		                "flatscoreid": 12,
		                "entryid": 12,
		                "eventid": 2,
		                "divisionid": 45,
		                "dist1": "50",
		                "dist1score": 294,
		                "dist2": "50",
		                "dist2score": 296,
		                "dist3": "50",
		                "dist3score": 0,
		                "dist4": "50",
		                "dist4score": 0,
		                "total": 590,
		                "created_at": "2018-10-13 00:23:01",
		                "updated_at": "2018-10-13 00:23:01",
		                "week": 0,
		                "inners": 9,
		                "max": 1,
		                "totalhits": 0
	                }
                ],
                "Men's Junior Compound": [
	                {
		                "firstname": "test",
		                "lastname": "person",
		                "gender": "m",
		                "entrycompetitionid": 16,
		                "eventcompetitionid": 12,
		                "roundid": 5,
		                "divisionname": "Junior Compound",
		                "bowtype": "compound",
		                "unit": "m",
		                "flatscoreid": 13,
		                "entryid": 13,
		                "eventid": 2,
		                "divisionid": 44,
		                "dist1": "50",
		                "dist1score": 297,
		                "dist2": "50",
		                "dist2score": 314,
		                "dist3": "50",
		                "dist3score": 312,
		                "dist4": "50",
		                "dist4score": 304,
		                "total": 1227,
		                "created_at": "2018-10-13 00:24:21",
		                "updated_at": "2018-10-13 00:24:21",
		                "week": 0,
		                "inners": 28,
		                "max": 4,
		                "totalhits": 0
	                }
                ],
  
            },
            "recurve": {
                "Women's Intermediate Recurve": [
                	{
	                	"firstname": "test",
	                	"lastname": "person",
	                	"gender": "f",
	                	"entrycompetitionid": 17,
	                	"eventcompetitionid": 12,
	                	"roundid": 4,
	                	"divisionname": "Masters 50-64 Recurve",
	                	"bowtype": "recurve",
	                	"unit": "m",
	                	"flatscoreid": 14,
	                	"entryid": 14,
	                	"eventid": 2,
	                	"divisionid": 71,
	                	"dist1": "60",
	                	"dist1score": 280,
	                	"dist2": "60",
	                	"dist2score": 270,
	                	"dist3": "60",
	                	"dist3score": 286,
	                	"dist4": "60",
	                	"dist4score": 276,
	                	"total": 1112,
	                	"created_at": "2018-10-13 00:28:00",
	                	"updated_at": "2018-10-13 00:28:00",
	                	"week": 0,
	                	"inners": 15,
	                	"max": 3,
	                	"totalhits": 0
                	},
                	{
	                	"firstname": "test",
	                	"lastname": "person",
	                	"gender": "f",
	                	"entrycompetitionid": 18,
	                	"eventcompetitionid": 12,
	                	"roundid": 4,
	                	"divisionname": "Masters 50-64 Recurve",
	                	"bowtype": "recurve",
	                	"unit": "m",
	                	"flatscoreid": 15,
	                	"entryid": 15,
	                	"eventid": 2,
	                	"divisionid": 71,
	                	"dist1": "60",
	                	"dist1score": 258,
	                	"dist2": "60",
	                	"dist2score": 276,
	                	"dist3": "60",
	                	"dist3score": 284,
	                	"dist4": "60",
	                	"dist4score": 285,
	                	"total": 1103,
	                	"created_at": "2018-10-13 00:28:00",
	                	"updated_at": "2018-10-13 00:28:00",
	                	"week": 0,
	                	"inners": 14,
	                	"max": 6,
	                	"totalhits": 0
                	}
                ],
                "Women's Masters 50-64 Recurve": [
                	{
	                	"firstname": "test",
	                	"lastname": "person",
	                	"gender": "m",
	                	"entrycompetitionid": 19,
	                	"eventcompetitionid": 12,
	                	"roundid": 3,
	                	"divisionname": "Senior Recurve",
	                	"bowtype": "recurve",
	                	"unit": "m",
	                	"flatscoreid": 16,
	                	"entryid": 16,
	                	"eventid": 2,
	                	"divisionid": 75,
	                	"dist1": "70",
	                	"dist1score": 282,
	                	"dist2": "70",
	                	"dist2score": 294,
	                	"dist3": "70",
	                	"dist3score": 272,
	                	"dist4": "70",
	                	"dist4score": 272,
	                	"total": 1120,
	                	"created_at": "2018-10-13 00:28:52",
	                	"updated_at": "2018-10-13 00:28:52",
	                	"week": 0,
	                	"inners": 22,
	                	"max": 3,
	                	"totalhits": 0
                	}
                ],
            }
        }
    }
}
```

## Example 2
### Request - ***event without competitionid (overall)***
### Event Type - ***competition***
	Example URL
    https://www.archeryosa.com/api/event/2018-test-event

**Return** __shortened and ID content replaced for example purpose__
``` json
{
    "success": true,
    "data": {
	        "event": {
	        "eventid": 2,
	        "eventtypeid": 1,
	        "organisationid": 1,
	        "clubid": 21,
	        "entryclose": null,
	        "start": "2018-05-26",
	        "end": "2018-05-26",
	        "daycount": 1,
	        "contactname": "steve",
	        "phone": "0211498154",
	        "email": "info@archeryosa.com",
	        "location": "Auckland Archery Club",
	        "cost": "$30",
	        "bankaccount": "01-1111-1111111-000",
	        "bankreference": "Surname as reference",
	        "schedule": "Start time: 9am",
	        "info": "Prize giving at 5pm",
	        "eventurl": "2018-test-event-2",
	        "entrylimit": null,
	        "eventname": "2018 Test Event"
        },
		"eventtype": "Competition",
        "results": {
            "compound": {
                "Women's Cadet Compound": [
                    {
                        "name": "test person",
                        "dist1": "2x WA720 50m",
                        "dist1score": 590,
                        "total": 590
                    }
                ],
                "Men's Masters 50-64 Compound": [
                    {
                        "name": "testperson",
                        "dist1": "2x WA720 50m",
                        "dist1score": 1298,
                        "total": 1298
                    },
                    {
                        "name": "testperson",
                        "dist1": "2x WA720 50m",
                        "dist1score": 1285,
                        "total": 1285
                    },
                    {
                        "name": "testperson",
                        "dist1": "2x WA720 50m",
                        "dist1score": 1284,
                        "total": 1284
                    }
                ],
                "Men's Senior Compound": [
                    {
                        "name": "test person",
                        "dist1": "2x WA720 50m",
                        "dist1score": 1316,
                        "total": 1316
                    }
                ]
            },
            "recurve": {
                "Women's Intermediate Recurve": [
                    {
                        "name": "test person",
                        "dist1": "2x WA720 45m",
                        "dist1score": 1012,
                        "total": 1012
                    }
                ],
                "Women's Masters 50-64 Recurve": [
                    {
                        "name": "test person",
                        "dist1": "2x WA720 60m",
                        "dist1score": 1112,
                        "total": 1112
                    },
                    {
                        "name": "test person",
                        "dist1": "2x WA720 60m",
                        "dist1score": 1103,
                        "total": 1103
                    }
                ]
            }
        }
    }
}
```

## Example 3
### Request - ***event without competitionid (overall)***
### Event Type - ***league***
	Example URL
    https://www.archeryosa.com/api/event/2017-outdoor-league-series-1

**Return** __shortened and ID content replaced for example purpose__
``` json
{
    "success": true,
    "data": {
        "event": {
            "eventid": 1,
            "eventtypeid": 2,
            "organisationid": null,
            "clubid": null,
            "entryclose": null,
            "start": "2017-11-20",
            "end": "2018-03-04",
            "daycount": 105,
            "contactname": null,
            "phone": null,
            "email": "steve.clifton@outlook.com",
            "location": null,
            "cost": null,
            "bankaccount": null,
            "bankreference": null,
            "schedule": null,
            "info": null,
            "eventurl": "2017-outdoor-league-series-1",
            "entrylimit": null,
            "eventname": "2017 Outdoor League Series"
        },
        "eventtype": "League",
        "results": {
            "compound": {
                "Compound": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "roundid": 2,
                        "divisionid": "11",
                        "divisionname": "Compound",
                        "bowtype": "compound",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "1070"
                        },
                        "average": {
                            "average": "71.3333"
                        },
                        "top10points": {
                            "points": null
                        }
                    },
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "roundid": 2,
                        "divisionid": "11",
                        "divisionname": "Compound",
                        "bowtype": "compound",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "3523"
                        },
                        "average": {
                            "average": "326.6667"
                        },
                        "top10points": {
                            "points": "27"
                        }
                    }
                ]
            },
            "recurve": {
                "Recurve": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "roundid": 2,
                        "divisionid": "12",
                        "divisionname": "Recurve",
                        "bowtype": "recurve",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "1471"
                        },
                        "average": {
                            "average": "98.0667"
                        },
                        "top10points": {
                            "points": "30"
                        }
                    },
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "f",
                        "roundid": 2,
                        "divisionid": "12",
                        "divisionname": "Recurve",
                        "bowtype": "recurve",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "3086"
                        },
                        "average": {
                            "average": "265.1333"
                        },
                        "top10points": {
                            "points": "82"
                        }
                    }
                ]
            },
            "barebow": {
                "Barebow": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "roundid": 2,
                        "divisionid": "81",
                        "divisionname": "Barebow",
                        "bowtype": "barebow",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "2279"
                        },
                        "average": {
                            "average": "215.6667"
                        },
                        "top10points": {
                            "points": "100"
                        }
                    },
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "f",
                        "roundid": 2,
                        "divisionid": "81",
                        "divisionname": "Barebow",
                        "bowtype": "barebow",
                        "unit": "m",
                        "roundname": "Outdoor League Series 30m",
                        "code": "OLS30",
                        "top10": {
                            "total": "1873"
                        },
                        "average": {
                            "average": "124.8667"
                        },
                        "top10points": {
                            "points": "55"
                        }
                    }
                ]
            }
        }
    }
}
```

## Example 4
### Request - ***event with competitionid (week)***
### Event Type - ***league***
	Example URL
    https://www.archeryosa.com/api/event/2017-outdoor-league-series-1/1

**Return** __shortened and ID content replaced for example purpose__
``` json
{
    "success": true,
    "data": {
        "event": {
            "eventid": 1,
            "eventtypeid": 2,
            "organisationid": null,
            "clubid": null,
            "entryclose": null,
            "start": "2017-11-20",
            "end": "2018-03-04",
            "daycount": 105,
            "contactname": null,
            "phone": null,
            "email": "steve.clifton@outlook.com",
            "location": null,
            "cost": null,
            "bankaccount": null,
            "bankreference": null,
            "schedule": null,
            "info": null,
            "eventurl": "2017-outdoor-league-series-1",
            "entrylimit": null,
            "eventname": "2017 Outdoor League Series"
        },
        "eventtype": "League",
        "results": {
            "barebow": {
                "Barebow": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "entrycompetitionid": 88,
                        "eventcompetitionid": 1,
                        "roundid": 2,
                        "divisionname": "Barebow",
                        "bowtype": "barebow",
                        "unit": "m",
                        "flatscoreid": 673,
                        "entryid": 56,
                        "eventid": 1,
                        "divisionid": 81,
                        "dist1": "30",
                        "dist1score": 288,
                        "dist2": null,
                        "dist2score": null,
                        "dist3": null,
                        "dist3score": null,
                        "dist4": null,
                        "dist4score": null,
                        "total": 288,
                        "created_at": "2018-10-04 00:24:15",
                        "updated_at": "2018-10-04 00:25:20",
                        "week": 12,
                        "inners": 3,
                        "max": 0,
                        "totalhits": 0,
                        "points": 9
                    }
                ]
            },
            "compound": {
                "Compound": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "m",
                        "entrycompetitionid": 32,
                        "eventcompetitionid": 1,
                        "roundid": 2,
                        "divisionname": "Compound",
                        "bowtype": "compound",
                        "unit": "m",
                        "flatscoreid": 709,
                        "entryid": 32,
                        "eventid": 1,
                        "divisionid": 11,
                        "dist1": "30",
                        "dist1score": 339,
                        "dist2": null,
                        "dist2score": null,
                        "dist3": null,
                        "dist3score": null,
                        "dist4": null,
                        "dist4score": null,
                        "total": 339,
                        "created_at": "2018-10-04 00:24:16",
                        "updated_at": "2018-10-04 00:24:16",
                        "week": 12,
                        "inners": 18,
                        "max": 7,
                        "totalhits": 0,
                        "points": 10
                    },
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "f",
                        "entrycompetitionid": 14,
                        "eventcompetitionid": 1,
                        "roundid": 2,
                        "divisionname": "Compound",
                        "bowtype": "compound",
                        "unit": "m",
                        "flatscoreid": 690,
                        "entryid": 14,
                        "eventid": 1,
                        "divisionid": 11,
                        "dist1": "30",
                        "dist1score": 318,
                        "dist2": null,
                        "dist2score": null,
                        "dist3": null,
                        "dist3score": null,
                        "dist4": null,
                        "dist4score": null,
                        "total": 318,
                        "created_at": "2018-10-04 00:24:16",
                        "updated_at": "2018-10-04 00:24:16",
                        "week": 12,
                        "inners": 10,
                        "max": 4,
                        "totalhits": 0,
                        "points": null
                    }
                ]
            },
            "recurve": {
                "Recurve": [
                    {
                        "firstname": "test",
                        "lastname": "person",
                        "gender": "f",
                        "entrycompetitionid": 77,
                        "eventcompetitionid": 1,
                        "roundid": 2,
                        "divisionname": "Recurve",
                        "bowtype": "recurve",
                        "unit": "m",
                        "flatscoreid": 724,
                        "entryid": 57,
                        "eventid": 1,
                        "divisionid": 12,
                        "dist1": "30",
                        "dist1score": 305,
                        "dist2": null,
                        "dist2score": null,
                        "dist3": null,
                        "dist3score": null,
                        "dist4": null,
                        "dist4score": null,
                        "total": 305,
                        "created_at": "2018-10-04 00:24:17",
                        "updated_at": "2018-10-04 00:25:00",
                        "week": 12,
                        "inners": 10,
                        "max": 2,
                        "totalhits": 0,
                        "points": 8
                    }
                ]
            }
        }
    }
}
```