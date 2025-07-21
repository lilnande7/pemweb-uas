# Music Rental System - Documentation

## Business Requirements Document (BRD) & Software Requirements Specification (SRS)

### 📋 **Project Overview**

**System Name**: Music Rental System (MRS)  
**Version**: 1.0.0  
**Development Framework**: Laravel 12 with Filament v3 Admin Panel  
**Database**: MySQL 
**Frontend**: Blade Templates with Alpine.js  
**API Documentation**: OpenAPI 3.0 with L5-Swagger  

---

## 🎯 **Business Requirements Document (BRD)**

### **1. Executive Summary**

Music Rental System adalah platform digital yang memungkinkan pelanggan untuk menyewa alat musik secara online dengan sistem manajemen yang terintegrasi. Sistem ini dikembangkan untuk mempermudah proses rental alat musik dengan memberikan kemudahan bagi pelanggan dan efisiensi operasional bagi penyedia layanan.

### **2. Business Objectives**

#### **2.1 Primary Goals**
- **Digitalisasi Proses Rental**: Mengubah proses rental manual menjadi sistem digital yang efisien
- **Customer Experience**: Memberikan pengalaman pelanggan yang seamless dari pencarian hingga pengembalian
- **Operational Efficiency**: Meningkatkan efisiensi operasional dengan otomatisasi proses administrasi
- **Data Management**: Centralized data management untuk customer, inventory, dan transaksi

#### **2.2 Business Benefits**
- Pengurangan waktu proses booking hingga 80%
- Peningkatan akurasi data inventory dan customer
- Real-time tracking untuk status pesanan
- Automated billing dan invoice generation
- Improved customer satisfaction melalui self-service portal

### **3. Stakeholder Analysis**

#### **3.1 Primary Stakeholders**
- **End Customers**: Individu/grup yang ingin menyewa alat musik
- **Business Owner**: Pemilik toko rental alat musik
- **Admin Staff**: Staff yang mengelola operasional harian
- **Customer Service**: Tim yang menangani customer support

#### **3.2 Secondary Stakeholders**
- **Delivery Partners**: Untuk layanan antar jemput
- **Financial Department**: Untuk proses payment dan accounting
- **IT Support**: Untuk maintenance dan technical support

### **4. Business Process Requirements**

#### **4.1 Customer Journey**
```
1. Browse Catalog → 2. Select Instrument → 3. Book Rental → 
4. Payment → 5. Delivery/Pickup → 6. Usage Period → 7. Return
```

#### **4.2 Admin Operations**
```
1. Inventory Management → 2. Order Processing → 3. Customer Management → 
4. Payment Tracking → 5. Delivery Coordination → 6. Return Processing
```

### **5. Success Metrics**

#### **5.1 Key Performance Indicators (KPIs)**
- **Conversion Rate**: Minimum 15% dari visitor menjadi customer
- **Order Processing Time**: Maksimal 30 menit dari booking ke konfirmasi
- **Customer Satisfaction**: Rating minimal 4.5/5.0
- **System Uptime**: 99.5% availability
- **Revenue Growth**: Target 25% increase dalam 6 bulan

---

## 🔧 **Software Requirements Specification (SRS)**

### **1. System Architecture**

#### **1.1 Technology Stack**
```
├── Backend Framework: Laravel 12
├── Admin Panel: Filament v3
├── Database: MySQL
├── Frontend: Blade Templates + Alpine.js
├── API: RESTful API with OpenAPI 3.0
├── Authentication: Laravel Sanctum
├── File Storage: Local/Cloud Storage
└── Deployment: Docker + Docker Compose
```

#### **1.2 System Components**
- **Frontend Application**: Customer-facing web interface
- **Admin Panel**: Business management interface
- **API Layer**: RESTful endpoints for mobile/external integration
- **Database Layer**: Data persistence and management
- **Storage System**: File and media management

### **2. Functional Requirements**

#### **2.1 User Management Module**

**2.1.1 Customer Registration & Authentication**
- **FR-001**: System harus memungkinkan customer untuk registrasi dengan email dan password
- **FR-002**: System harus memvalidasi uniqueness email dan format yang benar
- **FR-003**: System harus menyimpan informasi customer: first_name, last_name, email, phone, city, postal_code, id_card_number, address
- **FR-004**: System harus mendukung login/logout functionality

**2.1.2 Admin User Management**
- **FR-005**: System harus mendukung role-based access control (RBAC) dengan roles: super_admin, admin, staff
- **FR-006**: System harus memiliki user management interface di admin panel
- **FR-007**: System harus menyimpan avatar dan profile information untuk admin users

#### **2.2 Instrument Management Module**

**2.2.1 Inventory Management**
- **FR-008**: System harus menyimpan data alat musik: name, description, category, daily_rate, security_deposit, image
- **FR-009**: System harus mengelola availability status: is_available (boolean) dan quantity_available (integer)
- **FR-010**: System harus mendukung kategorisasi alat musik
- **FR-011**: System harus mengupdate availability secara real-time saat terjadi booking

**2.2.2 Catalog Display**
- **FR-012**: System harus menampilkan catalog alat musik di frontend dengan filtering dan search
- **FR-013**: System harus menampilkan detail alat musik dengan gambar, spesifikasi, dan harga
- **FR-014**: System harus menampilkan availability status pada setiap item

#### **2.3 Rental Order Management Module**

**2.3.1 Booking Process**
- **FR-015**: System harus memungkinkan customer untuk melakukan booking dengan data: instrument_id, rental_start_date, rental_end_date, quantity, customer_info, delivery_method
- **FR-016**: System harus memvalidasi availability sebelum konfirmasi booking
- **FR-017**: System harus menghitung total cost: subtotal, tax (10%), security_deposit, total_amount
- **FR-018**: System harus generate unique order_number dengan format "ORD-YYYY-NNNN"
- **FR-019**: System harus menyimpan delivery_method: pickup atau delivery

**2.3.2 Order Processing**
- **FR-020**: System harus memiliki status tracking: pending, confirmed, delivered, in_use, returned, completed, cancelled
- **FR-021**: System harus memiliki payment_status: pending, paid, partial, refunded
- **FR-022**: System harus auto-assign user_id untuk admin yang memproses order
- **FR-023**: System harus menyimpan notes untuk informasi tambahan

**2.3.3 Order Management (Admin)**
- **FR-024**: Admin harus dapat melihat, edit, dan update status semua orders
- **FR-025**: Admin harus dapat melakukan filtering orders berdasarkan status, tanggal, customer
- **FR-026**: Admin harus dapat melakukan bulk actions untuk multiple orders
- **FR-027**: System harus otomatis calculate subtotal dan total amount

#### **2.4 API Integration Module**

**2.4.1 RESTful API Endpoints**
- **FR-028**: System harus menyediakan API endpoint POST /api/booking/create-order untuk create rental order
- **FR-029**: System harus menyediakan API endpoint GET /api/booking/order/{orderNumber} untuk get order details
- **FR-030**: System harus menyediakan comprehensive OpenAPI documentation dengan L5-Swagger
- **FR-031**: API harus mengembalikan standardized JSON response format

**2.4.2 API Security & Validation**
- **FR-032**: API harus memvalidasi semua required fields sebelum processing
- **FR-033**: API harus mengembalikan appropriate HTTP status codes dan error messages
- **FR-034**: API harus menggunakan CSRF protection untuk security

### **3. Non-Functional Requirements**

#### **3.1 Performance Requirements**
- **NFR-001**: Page load time harus kurang dari 3 detik
- **NFR-002**: API response time harus kurang dari 2 detik
- **NFR-003**: System harus support minimum 100 concurrent users
- **NFR-004**: Database query optimization untuk large datasets

#### **3.2 Security Requirements**
- **NFR-005**: Password harus di-hash menggunakan bcrypt algorithm
- **NFR-006**: System harus menggunakan CSRF protection
- **NFR-007**: Input validation dan sanitization untuk semua user inputs
- **NFR-008**: Role-based access control untuk admin features

#### **3.3 Usability Requirements**
- **NFR-009**: User interface harus responsive untuk desktop dan mobile
- **NFR-010**: Admin panel harus user-friendly dengan intuitive navigation
- **NFR-011**: Error messages harus informatif dan user-friendly
- **NFR-012**: System harus memiliki search dan filtering capabilities

#### **3.4 Reliability Requirements**
- **NFR-013**: System availability 99.5% uptime
- **NFR-014**: Automated backup system untuk data protection
- **NFR-015**: Error logging dan monitoring system
- **NFR-016**: Graceful error handling tanpa system crash

### **4. Database Schema**

#### **4.1 Core Tables**

**users**
```sql
- id (bigint, primary key)
- avatar_url (string, nullable)
- name (string)
- email (string, unique)
- password (string)
- roles (relationship)
```

**customers**
```sql
- id (bigint, primary key)
- first_name (string)
- last_name (string)
- email (string, unique)
- phone (string)
- city (string)
- postal_code (string)
- id_card_number (string)
- address (text)
- created_at, updated_at (timestamps)
```

**instruments**
```sql
- id (bigint, primary key)
- name (string)
- description (text)
- category (string)
- daily_rate (decimal)
- security_deposit (decimal)
- is_available (boolean)
- quantity_available (integer)
- image (string, nullable)
- created_at, updated_at (timestamps)
```

**rental_orders**
```sql
- id (bigint, primary key)
- order_number (string, unique)
- customer_id (foreign key)
- user_id (foreign key, nullable)
- rental_start_date (date)
- rental_end_date (date)
- subtotal (decimal)
- tax_amount (decimal)
- security_deposit (decimal)
- total_amount (decimal)
- outstanding_amount (decimal)
- status (enum: pending, confirmed, delivered, in_use, returned, completed, cancelled)
- payment_status (enum: pending, paid, partial, refunded)
- delivery_method (enum: pickup, delivery)
- notes (text, nullable)
- created_at, updated_at (timestamps)
```

**rental_order_items**
```sql
- id (bigint, primary key)
- rental_order_id (foreign key)
- instrument_id (foreign key)
- quantity (integer)
- unit_price (decimal)
- total_price (decimal)
- created_at, updated_at (timestamps)
```

#### **4.2 Permission System Tables**
- **roles**: User roles management
- **permissions**: System permissions
- **model_has_roles**: User-role assignments
- **model_has_permissions**: Direct user permissions
- **role_has_permissions**: Role-permission assignments

### **5. API Documentation**

#### **5.1 Endpoint Overview**
```
POST /api/booking/create-order - Create new rental order
GET /api/booking/order/{orderNumber} - Get order details
GET /api/health - API health check
```

#### **5.2 API Response Format**
```json
{
    "success": true|false,
    "message": "Response message",
    "data": {
        // Response data object
    },
    "errors": {
        // Validation errors (if any)
    }
}
```

### **6. User Interface Requirements**

#### **6.1 Frontend Components**
- **Homepage**: Hero section with featured instruments
- **Catalog Page**: Grid/list view dengan filter dan search
- **Instrument Detail**: Detailed view dengan booking form
- **Booking Form**: Multi-step form dengan validation
- **Booking Success**: Confirmation page dengan order details
- **Track Order**: Order status tracking

#### **6.2 Admin Panel Components**
- **Dashboard**: Overview statistics dan recent activities
- **Order Management**: CRUD operations untuk rental orders
- **Instrument Management**: Inventory management interface
- **Customer Management**: Customer data management
- **User Management**: Admin user management
- **Role & Permissions**: Access control management

### **7. Integration Requirements**

#### **7.1 Internal Integrations**
- Frontend ↔ API integration untuk real-time updates
- Admin Panel ↔ Database untuk data management
- Notification system untuk order updates
- File upload system untuk instrument images

#### **7.2 External Integration Possibilities**
- Payment gateway integration (future enhancement)
- SMS/Email notification service
- Google Maps integration untuk delivery tracking
- Inventory sync dengan external POS systems

### **8. Deployment & Environment**

#### **8.1 Development Environment**
```
- PHP 8.2+
- Laravel 12
- MySQL
- Docker & Docker Compose
- Node.js untuk asset compilation
```

#### **8.2 Production Requirements**
```
- Web Server: Nginx
- SSL Certificate untuk HTTPS
- Automated backup system
- Monitoring dan logging tools
- CDN untuk static assets
```

### **9. Testing Requirements**

#### **9.1 Testing Scope**
- **Unit Testing**: Individual component testing
- **Integration Testing**: API endpoint testing
- **Functional Testing**: User journey testing
- **Performance Testing**: Load testing dengan concurrent users
- **Security Testing**: Vulnerability assessment

#### **9.2 Test Coverage**
- Minimum 80% code coverage untuk critical functions
- API endpoint testing dengan Postman/PHPUnit
- Frontend functionality testing
- Database integrity testing

### **10. Maintenance & Support**

#### **10.1 Regular Maintenance Tasks**
- Database optimization dan cleanup
- Security updates dan patches
- Performance monitoring dan tuning
- Backup verification dan restoration testing

#### **10.2 Support Documentation**
- User manual untuk end customers
- Admin guide untuk business operations
- API documentation untuk developers
- Troubleshooting guide untuk technical issues

---

## 📊 **Implementation Summary**

### **Completed Features**
✅ User Authentication
✅ Customer Registration & Management  
✅ Instrument Catalog & Management  
✅ Rental Order Creation & Processing  
✅ Admin Panel with CRUD Operations  
✅ Frontend Booking System  
✅ API Endpoints with OpenAPI Documentation  
✅ Database Schema & Relationships  
✅ Order Status Tracking  
✅ Automatic Pricing Calculation  

### **Technical Achievements**
✅ Laravel 12 dengan Filament v3 implementation  
✅ MySQL database dengan proper relationships  
✅ RESTful API dengan comprehensive documentation  
✅ Responsive frontend dengan Blade templates  
✅ Role-based access control (RBAC)  
✅ Real-time inventory management  
✅ Docker containerization  
✅ L5-Swagger API documentation  

### **System Capabilities**
- **Multi-user Support**: Customer dan admin dengan different access levels
- **Real-time Operations**: Instant availability updates dan order processing
- **Scalable Architecture**: Modular design untuk future enhancements
- **API-First Approach**: Ready untuk mobile app integration
- **Comprehensive Logging**: Activity tracking untuk audit trails
- **Data Integrity**: Foreign key constraints dan validation rules

---

## 🚀 **Conclusion**

Music Rental System telah berhasil dikembangkan sesuai dengan business requirements dan technical specifications. Sistem ini menyediakan complete solution untuk music instrument rental business dengan:

1. **End-to-End Customer Journey**: Dari discovery hingga booking completion
2. **Powerful Admin Tools**: Comprehensive management capabilities
3. **API Integration**: Ready untuk future mobile app development
4. **Scalable Architecture**: Dapat dikembangkan untuk fitur additional
5. **Security & Performance**: Built dengan best practices Laravel

Sistem ini belum siap untuk deployment.

---

**Document Version**: 1.0.0  
**Last Updated**: July 22, 2025  
**Author**: Fernanda
**Status**: Not Ready Yet

***Attachment***
-Homepage
<img width="1907" height="974" alt="image" src="https://github.com/user-attachments/assets/9729ee45-4d97-42b6-bad4-b903d1db7be2" />
<img width="1912" height="920" alt="image" src="https://github.com/user-attachments/assets/b907f7f2-f69a-4fce-86e0-32151e6b7e50" />
<img width="1890" height="918" alt="image" src="https://github.com/user-attachments/assets/35a5331e-6132-4a26-8752-973626d0f7d4" />

-Catalog Page: Grid/list view dengan filter dan search
<img width="1899" height="972" alt="image" src="https://github.com/user-attachments/assets/994d251c-7e64-4ec3-b3c2-80b96b827260" />
<img width="1905" height="921" alt="image" src="https://github.com/user-attachments/assets/400b1c18-3198-4828-8de5-a4e50b20ff6e" />

- **Instrument Detail**: Detailed view dengan booking form
<img width="1896" height="926" alt="image" src="https://github.com/user-attachments/assets/0eab04a0-2256-4435-8830-3953292d7b9b" />
<img width="1900" height="938" alt="image" src="https://github.com/user-attachments/assets/ad4c1c1d-d850-4818-9e3a-d99053980d96" />

- **Booking Form**: Multi-step form dengan validation
<img width="1445" height="963" alt="image" src="https://github.com/user-attachments/assets/7f44f2bf-5f7c-4288-bd4b-0b0c3cbed713" />
<img width="1302" height="843" alt="image" src="https://github.com/user-attachments/assets/e7ff3f4f-5452-4a3d-8b19-2575ad769873" />

- **Booking Success**: Confirmation page dengan order details 
<img width="1442" height="972" alt="image" src="https://github.com/user-attachments/assets/e7fe6bf5-ac54-4a7b-bc68-2cabae669406" />
<img width="1274" height="828" alt="image" src="https://github.com/user-attachments/assets/6a50feb7-5bdb-414a-b83c-ffb349fea04f" />


- **Track Order
<img width="1527" height="968" alt="image" src="https://github.com/user-attachments/assets/cab66484-f78e-48c7-a54b-81441e212d8b" />
<img width="1211" height="969" alt="image" src="https://github.com/user-attachments/assets/3b1e7cc1-63d8-4e26-b0d8-b2a8db79b4c9" />
<img width="1227" height="955" alt="image" src="https://github.com/user-attachments/assets/7aed7628-4af1-45ee-a06e-bd4ae82811a5" />


## Admin Panel ##
- login dengan akses https://localhost/admin

**Dashboard**
<img width="1919" height="1017" alt="image" src="https://github.com/user-attachments/assets/34ad7ac0-ae90-45c2-827c-79b1bfc574fc" />
<img width="1917" height="809" alt="image" src="https://github.com/user-attachments/assets/46d08760-7848-48d1-bab7-c67fc82ac58a" />
`Terdapat orderan rental sebelumnya yang masih dalam status pending (menunggu persetujuan admin)`

**Instrument Categories**
<img width="1919" height="970" alt="image" src="https://github.com/user-attachments/assets/981773d2-960e-4cf4-9f4a-8d99410d4857" />
`Alat musik berdasarkan kategori`
<img width="1907" height="973" alt="image" src="https://github.com/user-attachments/assets/ce76b13a-809f-4b49-94f0-8af355f1f016" />
`Admin dapat mengupdate alat musik, menghapus, dan membuat alat musik baru`

**Customer Management**
<img width="1919" height="971" alt="image" src="https://github.com/user-attachments/assets/abe3f8bd-526e-4bf6-97bc-0d1594536bf5" />
`Admin dapat menambahkan pelanggan baru, meng-update, dan menghapus.`
