# Changelog

All Notable changes to `omnipay/gocardlessv2` will be documented in this file

- 0.1.13 - Add some methods to the subscription response for returning data. 
- 0.1.12 - Switch from is_null() to empty() to handle empty arrays when stripping blank params
- 0.1.11 - Add some methods to the Bank Account for returning data
- 0.1.10 - Add a mechanism to the payment response for querying if the payment is outstanding without needing to know the statuses (which are now available as constants on the payment response)
- 0.1.9 - Params from abstract now returns core values, search mechanism on payments via customer reference.
- 0.1.8 - Add authenticate webhook method. Change error handling to omnipay standard method.
- 0.1.4 - Allow never-ending subscriptions
- 0.1.3 - Add rate handling to the send method - it will now sleep for 60 seconds before making a second attempt.
- 0.1.3 - ignore nulls on the bank account object.
- 0.1.2 - Switch customer bank account creation on the pro gateway to use params rather than an array
- 0.1.1 - Update to GoCardlessPro 1
- 0.1.0 - initial release of JS Flow for production
- 0.0.8 - standardise metadata to strings
- 0.0.7 - nullable app fees on create payment
- 0.0.6 - extend base response with standard function for link and metadata retrieval. Add more functions to the purchaseResponse object to wrap the GC Purchase object (notably formatting currency and creating DateTime objects)
- 0.0.5 - parseNotification restructured to accept optional signature
- 0.0.4 - modified the parseNotification to return an array of event results (more helpful than an array of requests)
