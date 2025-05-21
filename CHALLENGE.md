# Ridely - Technical Challenge for Solutions Architect

## Project Overview
Ridely is a cutting-edge application designed specifically for enhancing the taxi-riding experience in the heart of Aracaju. 
This project embodies the core functionalities necessary for a comprehensive ride-hailing application, offering users a seamless, efficient, 
and reliable way to navigate the bustling streets of Aracaju by taxi.

## Challenge Description
As a Solutions Architect, you are expected to analyze the current codebase and propose comprehensive improvements that would make this application 
production-ready, scalable, and maintainable. Your solution should demonstrate your ability to design and implement enterprise-grade architectures while 
considering real-world constraints and business requirements.

## Current Implementation Issues

### 1. Architecture & Design
- Monolithic architecture without clear service boundaries
- Missing domain-driven design principles
- No clear separation between domain logic and infrastructure
- No clear strategy for handling distributed transactions
- Lack of event-driven architecture for real-time features

### 2. Scalability & Performance
- No caching strategy implemented
- Missing database optimization (indexes, query optimization)
- No rate limiting or throttling mechanisms

### 3. Security
- Missing input validation and sanitization
- No CSRF protection
- Missing rate limiting
- No API authentication/authorization
- Missing security headers

### 4. Infrastructure & DevOps
- No containerization strategy (only database for dev environment)
- No infrastructure as code
- No logging strategy

### 5. Testing & Quality
- Missing unit tests
- No integration tests
- No end-to-end tests
- No code quality tools integration

### 6. Business Logic Issues
- REST API endpoints use incorrect HTTP resource naming and lack proper RESTful design principles (e.g. using POST for deleting data)
- Anyone can access all endpoints meaning that a passenger could accept a ride in beahalf of a driver
- Current system automatically assigns a driver instead of broadcasting to all available drivers
- No validation of duplicated rides, drivers and passengers
- No timeout mechanism for ride requests
- Driver can't update his information
- No driver history tracking
- No passenger registration system
- No passenger history tracking
- No ride cancellation policies
- No ride price calculation before acceptance

### Challenge

As a Solutions Architect, your task is to address and resolve the issues outlined in topics 1 through 5 by designing and implementing robust, 
production-ready solutions in code. You are also expected to create a high-level architectural diagram that illustrates your design decisions and the rationale behind them. 
Additionally, you should ensure that all business logic shortcomings are fully addressed, delivering solutions that meet both technical and stakeholder requirements.
