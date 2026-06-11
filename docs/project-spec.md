# Project Specification Update - Client-side PDF & Polling Real-time Dashboard

## Overview
This update details the technical architecture and specifications for migrating the document generation process from server-side (DomPDF) to client-side (native browser printing), disabling WebSockets (Laravel Reverb/Pusher), and using randomized interval polling (30-45s) for real-time dashboard updates.

## Core Features
1. **Client-side PDF Generation**: Native print-to-PDF triggered on the client's browser.
2. **Randomized Event Polling**: Heavy persistent WebSockets replaced with random 30-45s polling interval.
3. **Verification via Barcode/QR Hash**: Scan URL validation `/verifikasi/{hash}` remains active.

## Phase Plan
1. **Brief Parser**: Specifying requirements (`docs/project-spec.md`).
2. **DB Design**: Validating existing schema (`docs/db-schema.md`).
3. **Backend API**: Outlining API contracts (`docs/api-contract.yaml`).
4. **Frontend Build**: Implementing Vue.js pages.
5. **QA Validation**: Validating changes.
