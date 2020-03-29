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
	- 'competitions' - Object of Competition names, key'd by their competitionid

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
            "Mens Cadet Compound": {
                "0": {
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "club": "",
                    "round": "WA720 50m",
                    "dist1": 334,
                    "dist2": 340,
                    "dist3": "",
                    "dist4": "",
                    "total": 674
                },
                "1": {
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "club": "",
                    "round": "WA720 50m",
                    "dist1": 335,
                    "dist2": 333,
                    "dist3": "",
                    "dist4": "",
                    "total": 668
                },
                "rounds": {
                    "dist1": "50",
                    "dist2": "50",
                    "dist3": "",
                    "dist4": "",
                    "total": "Total",
                    "unit": "m"
                }
            },
            "Mens Junior Compound": {
            
                "0": {
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "club": "",
                    "round": "WA720 50m",
                    "dist1": 300,
                    "dist2": 307,
                    "dist3": "",
                    "dist4": "",
                    "total": 607
                },
                "rounds": {
                    "dist1": "50",
                    "dist2": "50",
                    "dist3": "",
                    "dist4": "",
                    "total": "Total",
                    "unit": "m"
                }
            },
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
            "Mens Senior Compound": {
            
                "0": {
                    "217": 678,
                    "218": 684,
                    "219": 686,
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 2048
                },
                "rounds": {
                    "217": "WA720 50m",
                    "218": "WA720 50m",
                    "219": "WA720 50m"
                }
            
            },
            "Womens Cadet Compound": {
            
                "0": {
                    "217": 595,
                    "218": 581,
                    "219": 573,
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 1749
                },
                "rounds": {
                    "217": "WA720 50m",
                    "218": "WA720 50m",
                    "219": "WA720 50m"
                }
            
            },
            "Mens Cadet Recurve": {
            
                "0": {
                    "217": 558,
                    "218": 596,
                    "219": 581,
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 1735
                },
                "1": {
                    "217": 613,
                    "218": 596,
                    "219": "",
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 1209
                },
                "2": {
                    "217": 527,
                    "218": 555,
                    "219": 553,
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 1635
                },
                "3": {
                    "217": 542,
                    "218": 545,
                    "219": 559,
                    "archer": "<a href=\"/profile/public/test\">test</a>",
                    "total": 1646
                },
                "rounds": {
                    "217": "WA720 60m",
                    "218": "WA720 60m",
                    "219": "WA720 60m"
                }
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