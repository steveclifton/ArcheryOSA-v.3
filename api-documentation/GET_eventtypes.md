# Event Types

## Method
`GET` <br>
`/api/eventtypes`
***

## Description
Returns the types of events ArcheryOSA currently handles
***

## Parameters
None

***

## Return format
An array with the following keys and values:

- **success** — Bool - whether the request was successful or not
- **data** — Array - container for the return data:
    - 'types' - Object of the events types

***



## Example 1
### Request - ***event with competitionid***
	Example URL
    https://www.archeryosa.com/api/event/2018-test-event/12

``` json
{
    "success": true,
    "data": {
        "types": [
            {
                "eventtypeid": 1,
                "label": "Competition",
                "description": "Single or multiple day event - may have multiple differing competitions",
                "created_at": "2018-08-09 19:58:12",
                "updated_at": "2018-08-09 19:58:12"
            },
            {
                "eventtypeid": 2,
                "label": "League",
                "description": "Multi-week event",
                "created_at": "2018-08-04 13:20:59",
                "updated_at": "2018-08-04 13:20:59"
            }
        ]
    }
}
```
