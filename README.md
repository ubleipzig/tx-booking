# ubl/booking
Manage bookings of rooms for typo3 frontend users

This extension was created out of the need to manage bookings for
group study rooms available to students and patrons of leipzig university library.

Anonymous website visitors get an overview of rooms and occupation in a
specific period of time, managable in the typo3-backend.

Logged in users additionally can book timeslots of rooms according to the defined
maximum of bookings per day and location.

## Requirements
* Typo3 > 7.6 < 8.7.99
* PHP >= 7.0
* Icon font >= 0.9.0

We have not tested it with higher versions but the codebase should be sufficient.

## Usage
This extension provides a plugin which has to be assign to the designated page.

### Create a Location
Add a *New content element->Plugins->General Plugin*.
Under Tab *Plugin* choose **Booking for Rooms**.

#### Configuration
After switching back to the Tab *Plugin* you have several options to specify:

* **Maximum bookings per day and user**
 Defines how many bookings a user can place per day, regardless on how many rooms
 you will make available on this page. Defaults to 5 bookings if nothing is specified.
* **Count of weeks available for booking in advance**
 Defines how many weeks users will be able to book in advance.
 Current week plus what you define here. Default is 2 weeks if nothing specified.
* **Count of weeks available for looking back**
 Defines how many weeks one can go back to see the occupation.
 Defaults to 1 week if nothing is specified.
* **Administrative accounts**
 Defines the frontend user accounts that are treated as admins. they are not limited by
 all prior settings.

On the tab *Behaviour* one has to specify the *Record Storage Page* where rooms,
bookings, opening hours and closing days will be stored.

If not overridden as described in the **Advanced Customization**-Section
all opening hours and closing days created here count for all rooms created here.

#### Create a Room
To create a room go to the page that you specified as *Record Storage Page*
in the *Configuration*-Section earlier, *Create a new Record* and choose **Rooms**

You have to provide at least a *Room name*.

#### Enable Stylesheets
To enable the default CSS stylesheets that come with the extension one has to include them into the root template. Go to *Web->Template*, select *Info/Modify* in the Dropdown-box and click on *Edit the whole template record*. Select the tab *Includes*, add *Booking CSS Styles (booking)* from *Available Items* to *Selected Items* and click *Save*. You have now added the extension's stylesheet to the page and enabled icon font styles.

### define Closing Days
Closing days are useful for bank holidays for example.

To create a closing day go to the page that you specified as *Record Storage Page*
in the *Configuration*-Section earlier, *Create a new Record* and choose **Closing Day**

You have to provide at least a *Closing day name* and a *Date*. The days are shown
as non-bookable in week overview and its not possible to create bookings from the frontend
on these days. However there is no validation on bookings one creates from backend but they are not
shown in frontend either.

### define Opening Hours
By default all hours of all days a week are bookable. With opening hours one can define
the opening hours of a day.

To create opening hours for a day go to the page that you specified as *Record Storage Page*
in the *Configuration*-Section earlier, *Create a new Record* and choose **Opening Hours**

Select the day of week you want to specify he opening hours for and then select the hours
of duty.

If you select no hours at all the day is closed for bookings entirely. Like this one
 can create weekly closed days i.e. sunday.

## Advanced Customization
By default closing days and opening hours count for all rooms. However if you want to define
different rules for different rooms you can create a new *Record Storage Page* and specify it
for the designated room(s).

Just open the already created room-record and register newly created *Record Storage Page*
under *Opening times storage pages*.

You even can register multiple pages if you want to make use of the inheritance principle.
The rule is, what comes first counts. For example if you define opening hours for monday on
two pages the opening hours of the page that is listed first are taken.

Closing days are inherited as well. However overriding makes no sense here.

To get a better overview of bookings made for a room you also can specify a storage page
for a rooms bookings.

To do so register the designated storage page under *Booking storage page*
and you are good to go. Future bookings are stored under that page, but bookings
from the plugin's storage page are taken in account as well so you dont have to
worry about old bookings.

## Cleanup old Bookings
There is a cleanup-command which can be used with typo3's commandline interface
or the scheduler to clean up old bookings.
Be aware that plugin configured looking back weeks are not respected here.
You specify the count of weeks to keep from now on.

Go to *Scheduler->Add Task*
* from *Class* choose **Extbase CommandController Task**
* specify the *Frequency* according to your needs (once a week is sufficient)
* from the list of *CommandController Command* choose **Booking Cleanup: cleanupBookings**
* save the task **important! otherwise the form element for the argument is not appearing
* specify the count of weeks to keep in the *weeks* argument field. 0 (zero) or empty
 means that all bookings prior to the current week are removed

## Upgrade to 1.1.0
In version 1.1.0 the Naming changed from `ubl_booking` to `booking`, class namespace changed from
`LeipzigUniversityLibrary\UblBooking` to `Ubl\Booking`, composer-name changed from `ubl/ubl_booking` to `ubl/booking`

In order to keep Your data follow these steps to convert them to the new names:

0. set Webpage in maintenance mode
1. install the new version as next to the old one, so you have `booking` and `ubl_booking` installed and activated.
2. deactivate `ubl_booking`
3. under *actions* run *Execute update script* for `bookings`
4. replace plugin in each page
5. enable stylesheets according to *Enable Stylesheets* above
6. remove extension `ubl_booking`
7. disable maintenance mode
