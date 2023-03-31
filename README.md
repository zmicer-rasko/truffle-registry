[Truffle](https://en.wikipedia.org/wiki/Truffle) is the diamond of the kitchen.
Millions of people hunt for truffles and thousands of restaurants compete for
the best specimens.

Our company connects truffle hunters and large restaurant chains. To do this,
hunters are provided with a form for registering found truffles. In addition,
we also cooperate with a large cultivator of homemade truffles, which shares
the register of their products with us.

To meet the needs of our customers, we have developed a service that allows
authorized truffle hunters to register their finds through a convenient API
that allows them to report the price and weight of a truffle.

In addition, we process the manufacturer's database, which is sent to us via
FTP. Unfortunately, they cannot share data only about new truffles, so they
send their full register of truffles every time. Now there are only about
10,000 units in their register, but according to their plan, aggressive 
cultivation will allow them to accumulate more than a million truffles in stock 
by the end of the year.

Our service must process data from these sources and compile a common register 
of truffles in a convenient CSV format, which restaurant chains will take over 
the FTP protocol.

Our internal development team has successfully implemented of this service and
our QA team made sure that what has been done is exactly what we wanted.

However since this service is incredibly important for our business, we 
decided to involve an independent expert to assess the technical quality of
the result. The solution will evolve in the future, so the quality of the 
code is of most importance.

**Your task will be:**

- _[mandatory]_ conduct a code review and describe the problems, if any;
- _[mandatory]_ describe the improvements if they are required;
- _[preferably]_ run the project using any convenient tool, like Laravel Sail;
- _[preferably]_ implement these improvements and make a pull request;
- _[optional]_ ensure the quality of the tests;
