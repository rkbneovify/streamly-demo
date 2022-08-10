//
//  LoginResponse.swift
//  Streamly
//
//  Created by Rohit Singh on 5/18/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
struct LoginResponse : Codable {
    let subscribed          : Bool?
    let subscribedmsgis     : String?
    let sessionid           :String?
    let deviceType         :String?
    let personaldeviceid    :String?
    let personaldevicenamePopup: Bool?
    let access_token        :String?
    let connected_device    :String?
    let device_info         :String?
    let success             :Bool?
    let message             :String?
    let status              :Int?
    let response_code       :Int?
    let termandcondition    :Bool?
    let token_type          :String?
    let user                :User?
    let rooms                : [Room]?
    let userrole : String?
    let expires_in: Int?
    var data: String?
    let houroffset: String?
    
    enum CodingKeys : String, CodingKey {
        case subscribed
        case subscribedmsgis
        case deviceType = "device_type"
        case personaldevicenamePopup = "personaldevicename_popup"
        case personaldeviceid
        case sessionid
        case access_token = "access_token"
        case connected_device = "connected_device"
        case device_info = "device_info"
        case success = "success"
        case message = "message"
        case status = "status"
        case response_code = "response_code"
        case token_type = "token_type"
        case user = "user"
        case termandcondition = "termandcondition"
        case rooms
        case userrole
        case expires_in = "expires_in"
        case data
        case houroffset
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        deviceType = try values.decodeIfPresent(String.self, forKey: .deviceType)
        sessionid = try values.decodeIfPresent(String.self, forKey: .sessionid)
        personaldeviceid = try values.decodeIfPresent(String.self, forKey: .personaldeviceid)
        personaldevicenamePopup = try values.decodeIfPresent(Bool.self, forKey: .personaldevicenamePopup)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        message = try values.decodeIfPresent(String.self, forKey: .message)
        status = try values.decodeIfPresent(Int.self, forKey: .status)
        access_token = try values.decodeIfPresent(String.self, forKey: .access_token)
        connected_device = try values.decodeIfPresent(String.self, forKey: .connected_device)
        device_info = try values.decodeIfPresent(String.self, forKey: .device_info)
        token_type = try values.decodeIfPresent(String.self, forKey: .token_type)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        user = try values.decodeIfPresent(User.self, forKey: .user)
        termandcondition  = try values.decodeIfPresent(Bool.self, forKey: .termandcondition)
        rooms = try values.decodeIfPresent([Room].self, forKey: .rooms)
        userrole = try values.decodeIfPresent(String.self, forKey: .userrole)
        expires_in = try values.decodeIfPresent(Int.self, forKey: .expires_in)
        data = try values.decodeIfPresent(String.self, forKey: .data)
        subscribedmsgis = try values.decodeIfPresent(String.self, forKey: .subscribedmsgis)
        subscribed = try values.decodeIfPresent(Bool.self, forKey: .subscribed)
        houroffset = try values.decodeIfPresent(String.self, forKey: .houroffset)
    }
    
}

// MARK: - UserLogin
struct User: Codable {
    var id: Int?
 //   var providerName, providerID: JSONNull?
    var name, email: String?
 //   var contact, gender, dob: JSONNull?
    var zipcode: String?
   // var address: JSONNull?
    var country: String?
   // var googleID, lastLogin: JSONNull?
    var emailVerifiedAt: String?
    var isActive: Int?
    var createdAt, updatedAt, stripeID, cardBrand: String?
    var cardLastFour: String?
 //   var trialEndsAt, avatar: JSONNull?
    var roles: [Role]?

    enum CodingKeys: String, CodingKey {
        case id
       // case providerName = "provider_name"
        // case providerID = "provider_id"
        case name, email, zipcode,  country//contact, gender, dob, address,
        // case googleID = "google_id"
        // case lastLogin = "last_login"
        case emailVerifiedAt = "email_verified_at"
        case isActive = "is_active"
        case createdAt = "created_at"
        case updatedAt = "updated_at"
        case stripeID = "stripe_id"
        case cardBrand = "card_brand"
        case cardLastFour = "card_last_four"
        // case trialEndsAt = "trial_ends_at"
        case  roles //avatar,
    }
}

// MARK: - Role
struct Role: Codable {
    var id: Int?
    var name, displayName, roleDescription: String?
  //  var createdAt, updatedAt: JSONNull?
    var pivot: Pivot?

    enum CodingKeys: String, CodingKey {
        case id, name
        case displayName = "display_name"
        case roleDescription = "description"
        // case createdAt = "created_at"
        // case updatedAt = "updated_at"
        case pivot
    }
}

// MARK: - Pivot
struct Pivot: Codable {
    var userID, roleID: Int?

    enum CodingKeys: String, CodingKey {
        case userID = "user_id"
        case roleID = "role_id"
    }
}

struct Room: Codable {
    
    let id        :   Int?
    let user_id    : Int?
    let hotel_room         : String?
   // let session_id       : String?
    let created_on: String?
    let modified_on: String?
    
    enum CodingKeys : String, CodingKey {

        case id
        case user_id
        case hotel_room
       // case session_id
        case created_on
        case modified_on
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        id = try values.decodeIfPresent(Int.self, forKey: .id)
        user_id = try values.decodeIfPresent(Int.self, forKey: .user_id)
        hotel_room = try values.decodeIfPresent(String.self, forKey: .hotel_room)
        //session_id = try values.decodeIfPresent(String.self, forKey: .session_id)
        created_on = try values.decodeIfPresent(String.self, forKey: .created_on)
        modified_on = try values.decodeIfPresent(String.self, forKey: .modified_on)
    }
}

struct FailedResponse: Codable {
    var success: Bool?
    var response_code: Int?
    var message: String?
    var data: String?
    var loggedindeviceinfolist: Loggedindeviceinfolist?
    enum CodingKeys : String, CodingKey {
        case loggedindeviceinfolist
        case success
        case response_code
        case message
        case data
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        message = try values.decodeIfPresent(String.self, forKey: .message)
        data = try values.decodeIfPresent(String.self, forKey: .data)
        if let listOFDevice = try values.decodeIfPresent(Loggedindeviceinfolist.self, forKey: .loggedindeviceinfolist) {
            loggedindeviceinfolist = listOFDevice
        }
    }
}

struct VisbilityInfo: Codable {
    var visible: Bool?
    
    enum CodingKeys : String, CodingKey {
        case visible
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        visible = try values.decodeIfPresent(Bool.self, forKey: .visible)
    }
}
