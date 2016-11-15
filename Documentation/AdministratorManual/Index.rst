.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator manual
====================


Target group: **Administrators**



Installation
^^^^^^^^^^^^
1. Install the extension just like any other extension from the TYPO3 extension repository. 
2. Create a page where you want to view the calendars of the booking objects
3. Insert the plugin to the page  and set the startingpoint to the page/folder were you want to store the data.
4. Now, create some booking objects with the list modul of TYPO3 in your record storing page.
5. In the field Duty hours enter a comma separated list of the duty hours of the object.
6. That's all, you can view the page for booking now.
7. A click on a date will lead you to the booking form where a frontend user can book the object.

.. figure:: ../Images/AdministratorManual/ubleipzigbookingObject.png
	:width: 600px
	:alt: Booking object



Reference
^^^^^^^^^

.. _plugin-tx-booking-pi1:

plugin.tx\_booking\_pi1
^^^^^^^^^^^^^^^^^^^^^^^

.. _templateFile:

templateFile
""""""""""""

.. container:: table-row

   Property
         templateFile

   Data type
         string

   Description
         Define the template file

   Default
         typo3conf/ext/ubleipzigbooking/pi1/template.html


.. _cssFile:

cssFile
""""""""""""

.. container:: table-row

   Property
         cssFile

   Data type
         string

   Description
         Define the css file

   Default
         typo3conf/ext/ubleipzigbooking/pi1/layout.css

.. _enableQuarterHourBooking:

enableQuarterHourBooking
""""""""""""""""""""""""

.. container:: table-row

   Property
         enableQuarterHourBooking

   Data boolean
         0

   Description
         If set, quarter hour booking is enabled

   Default
         

.. _limitPreviewToDays:

limitPreviewToDays
""""""""""""""""""

.. container:: table-row

   Property
         limitPreviewToDays

   Data int
         

   Description
         If set, limit the preview to n days

   Default






Hooks
^^^^^
There are two hooks in the bookingFormView implemented.
BookingFormHeaderHook and bookingFormDataHook.

These hooks can be called from your extension like this:
in your localconf.php
// hook for booking
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormHeaderHook'][] = 'EXT:ubleipzigbookinghooks/pi1/class.tx_ubleipzigbookinghooks_pi1.php:tx_ubleipzigbookinghooks_pi1';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormDataHook'][] = 'EXT:ubleipzigbookinghooks/pi1/class.tx_ubleipzigbookinghooks_pi1.php:tx_ubleipzigbookinghooks_pi1';
And the functions
function bookingFormDataHook(&$marks, $row, $conf, $obj) {
...
}
And 
function bookingFormHeaderHook(&$marks, $row, $conf, $obj) {
	$marks['###DELETE###']= 'xxx';
}






FAQ
^^^

