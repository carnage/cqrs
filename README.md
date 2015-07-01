# cqrs
Cqrs module for ZF2 taking heavy inspiration from broadway https://github.com/qandidate-labs/broadway designed to fit in with ZF2's design patterns.

This module is a work in progress, check back later.

# Licence

Licenced under AGPL - the source code release clause ONLY applies to modifications of this library not to your application code that uses this library see: http://blog.mongodb.org/post/103832439/the-agpl

# TODO

* upgrade to zf2.5 only pull in bits that are required (eg exclude zend db in favor of doctrine dbal)


# NOTES

Event Namespace contains 5 things:

- Event Manager: for classes/code that handle the triggered events and hand off to
- Event Subscribers: base classes/code for objects that handle (listen to) events
- Event Listeners: basic event listening
- Projections: event listeners which update read models (projections); will contain code to support this function eventually
- Sagas: event listeners which handle business processes as state machines; will contain code to support creation of sagas
