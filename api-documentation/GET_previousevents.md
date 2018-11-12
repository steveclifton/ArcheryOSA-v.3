# Previous Events

## Method
`GET` <br>
`/api/previousevents`

***

## Description
Returns all the previously created events
***

## Parameters
None
***

## Return format
An array with the following keys and values:

- **success** — Bool - whether the request was successful or not
- **data** — Array - container for the return data:
    - 'events' — Array of event objects;

***

## Example
**Request**

    https://www.archeryosa.com/api/eventtypes

**Return** __shortened for example purpose__
``` json
{
    "success": true,
    "data": {
        "events": [
            {
                "eventid": 8,
                "eventname": "2018 Test Event",
                "eventtypeid": 1,
                "organisationid": 1,
                "clubid": 1,
                "entryclose": "2018-11-17",
                "start": "2018-12-01",
                "end": "2018-12-01",
                "daycount": 1,
                "contactname": "steve",
                "phone": "0211111111",
                "email": "info@archeryosa.com",
                "location": "Auckland Archery Club",
                "cost": "$30",
                "bankaccount": "01-1111-11111111-000",
                "bankreference": "Surname as reference",
                "schedule": "Start time: 10am saturday morning",
                "info": "Uniform is required",
                "eventurl": "2018-test-event",
                "entrylimit": null,
                "eventstatus": "Open",
                "clubname": "Auckland Archery Club",
                "eventurlfull": "https://www.archeryosa.com/event/details/2018-test-event-8",
                "competitions": [
                    {
                        "eventcompetitionid": 12,
                        "date": "2018-12-01",
                        "eventcompetitionname": "Saturday",
                        "eventid": 8,
                        "roundids": null,
                        "currentweek": 1,
                        "location": "",
                        "schedule": "",
                        "divisionids": null,
                        "divisons": [
                            {
                                "divisionid": 14,
                                "label": "Recurve Barebow",
                                "code": "RBB"
                            },
                            {
                                "divisionid": 39,
                                "label": "Senior Barebow",
                                "code": "CMB"
                            },
                        ],
                        "rounds": [
                            {
                                "roundid": 3,
                                "label": "2x WA720 70m",
                                "type": "o",
                                "organisationid": 2,
                                "code": "wa720 70m",
                                "unit": "m",
                                "dist1": "70",
                                "dist1max": "360",
                                "dist2": "70",
                                "dist2max": "360",
                                "dist3": "70",
                                "dist3max": "360",
                                "dist4": "70",
                                "dist4max": "360",
                                "visible": 1,
                                "totalmax": "1440",
                                "created_at": "2017-11-29 08:38:54",
                                "updated_at": "2018-10-30 08:57:35",
                                "createdby": 0
                            },
                            {
                                "roundid": 4,
                                "label": "2x WA720 60m",
                                "type": "o",
                                "organisationid": 2,
                                "code": "wa720 60m",
                                "unit": "m",
                                "dist1": "60",
                                "dist1max": "360",
                                "dist2": "60",
                                "dist2max": "360",
                                "dist3": "60",
                                "dist3max": "360",
                                "dist4": "60",
                                "dist4max": "360",
                                "visible": 1,
                                "totalmax": "1440",
                                "created_at": "2017-11-29 08:42:07",
                                "updated_at": "2018-10-30 10:48:06",
                                "createdby": 0
                            },
                            {
                                "roundid": 5,
                                "label": "2x WA720 50m",
                                "type": "o",
                                "organisationid": 2,
                                "code": "wa720 50m",
                                "unit": "m",
                                "dist1": "50",
                                "dist1max": "360",
                                "dist2": "50",
                                "dist2max": "360",
                                "dist3": "50",
                                "dist3max": "360",
                                "dist4": "50",
                                "dist4max": "360",
                                "visible": 1,
                                "totalmax": "1440",
                                "created_at": "2017-11-29 08:43:12",
                                "updated_at": "2018-10-30 08:57:44",
                                "createdby": 0
                            },
                        ]
                    }
                ]
            },
        ]
    }
}
```
