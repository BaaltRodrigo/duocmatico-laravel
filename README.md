# Duocmatico

> [!IMPORTANT]
> We know we are using the word Duoc. But this project is not _fully_ related to Duoc UC. We were students and this is our project for the title.

This is the backend for a tool called Duocmatico. A web app to help Duoc UC students to prepare their academic season in a better way. Getting rid of the excel... Oh how we hate that excel file with the sections.

We use that excel actually to fill all the necessary data so the students (and teachers) can built their calendars.  
Just tell us your career and campus, we show you the sections available to you ;)

## Some of the conventions we are using on Duocmatico.

To give some structure and prevent the classic of "Th1S w4y Is BetT3r", some conventions are needed.

1. The first day of the week is Sunday. That means, if we enumerate the days of the week from 0 to 6. Sunday is 0.
2. We never delete a calendar.

### Response content to the clients

There is a convention used on the resources Duocmatico sent to the clients. This resources are JSON resources and we follow the [JsonApi standards](https://jsonapi.org/) or at least try to follow it the best we can.

That means that you will see 2 types of resources. Model resources and Identifier resources. 

#### Model Resource

This is the full data of a resource. This include its relationship to other resources, metadata and basically the full information of the resource.

#### Identifier Resources

This type of resource is meant to be included on the _relationships_ of other models.

This is useful to reduce the data we sent to the client and optimize the http request.

This will help also to reduce the DB workload. But no, we do not pick only the data we need to fulfill the request from the database. This is not our priority for the time now... 


## My data, what do you do with my data? Are you selling it?

First thing first. No. We do not sell or share any of your data. But we need to difference what is **your** data and **Duocmatico's data**

### What data is yours

Actually, we need so little of "your" data. Only an **email** to do a few things.
1. Identify you inside the app.
2. Get your data synced between devices.
3. Measure the use of Duocmatico.

## Who can use this API? 

At this moment, only the frontend of Duocmatico is meant to be using this API. But we do not close the possibility on open some of the endpoints to be used by students or every one who wants to try it.

