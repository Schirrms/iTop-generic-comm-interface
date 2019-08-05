# iTop-generic-comm-interface
Create a generic network interface, instead of theexisting Network an SAN interface

Itop com with a network interface really IP oriented. If you add the datacenter module, then you'll also ha ve a SAN interface type.

I plan to create a more generic kind of interface, with a 'connector type' (Fiber, RJ(45, RS-232) and a 'protocol-type' (Ethernet, FC, FCoE...)

This should stay on the OSI level 1 or 2, I'm not sure (yet) that this should include high level information (IP address, gateway...)

Of course, I'll have to build also a 'iTop-generic-switch' CI to connect the whole together !

History
=======

0.1.1 : the generic interface is now in an useable state, and visible from Server and Enclosure
        This is still a work in progress, nothing is considered 'definitive' at this state
        No named field at this stage
