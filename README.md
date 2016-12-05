# cqrs
Cqrs module for ZF2 taking heavy inspiration from broadway https://github.com/qandidate-labs/broadway designed to fit in with ZF2's design patterns.

This module is a work in progress, check back later.

It is suggested that you maintain your own fork until a tagged version of this library is release.

# Licence

Licenced under MIT 

# TODO

* upgrade to zf2.5 only pull in bits that are required (eg exclude zend db in favor of doctrine dbal)


# NOTES

Event Namespace contains 5 things:

- Event Manager: for classes/code that handle the triggered events and hand off to
- Event Subscribers: base classes/code for objects that handle (listen to) events
- Event Listeners: basic event listening
- Projections: event listeners which update read models (projections); will contain code to support this function eventually
- Sagas: event listeners which handle business processes as state machines; will contain code to support creation of sagas
