# Action-Domain-Responder (ADR) implementation for Laravel

* Installation
* Introduction
    * ADR
* Usage 

# Installation

```bash
composer require hydreflab/laravel-adr
```

Package requires Laravel >= 5.5. 

No additional service provider registration is required as package uses auto-discovery feature.

# Introduction

Some time ago, Mr. Paul M. Jones proposed an _Action Domain Responder (ADR)_ pattern, which is an alternative
to _Model View Controller (MVC)_ interface pattern. _ADR_ pattern is better suited for server-side applications
operating in the request/response concept.

_ADR_ pattern is described [here](https://github.com/pmjones/adr).

## ADR

_ADR_ introduces objects/responsibilities that correspond to the ones from _MVC_. However, directions of 
communication are a bit different.

_ADR_ defines three components:
* action (you can think of it as a controller or a request handler),
* domain (business logic),
* responder (element that is responsible for building response).

This package introduces base version of two of those components: an action and a responder.

Domain part is related to the business logic and it is only developer's responsibility how to model and use that,
if it is either strict DDD approach (repositories, use cases and everything) or typical Laravel models, or some service 
layer or something else.

In _ADR_ pattern, the action is responsible to call the domain and then pass eventual domain processing result to
the responder. So, basically, actions are one-method (thin) controllers, thus we can call it request handlers. Since
actions are thin controllers, it is quite handy to use them as invokable classes, which also simplifies testing.

Responder in _ADR_ is the presentation layer. Responder receives necessary data form the action and builds response that
is send back to the client. It is also possible, and sometimes handy, to include content negotiation within responder, 
i.e. prepare response in the format that suits the client best, based on request accept header.

# Usage

## Action & Routing

## Responder

## Console

# Example