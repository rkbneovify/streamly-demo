//
//  LoginResponse.swift
//  Streamly
//
//  Created by Rohit Singh on 5/18/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
struct LoginResponse : Codable {
    
    let access_token        :String?
    let connected_device    :String?
    let device_info         :String?
    let success             :Bool?
    let message             :String?
    let status              :Int?
    let response_code       :Int?
    let token_type          :String?
    let user                :User?
    
    enum CodingKeys : String, CodingKey {
        
        case access_token = "access_token"
        case connected_device = "connected_device"
        case device_info = "device_info"
        case success = "success"
        case message = "message"
        case status = "status"
        case response_code = "response_code"
        case token_type = "token_type"
        case user = "user"
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        message = try values.decodeIfPresent(String.self, forKey: .message)
        status = try values.decodeIfPresent(Int.self, forKey: .status)
        access_token = try values.decodeIfPresent(String.self, forKey: .access_token)
        connected_device = try values.decodeIfPresent(String.self, forKey: .connected_device)
        device_info = try values.decodeIfPresent(String.self, forKey: .device_info)
        token_type = try values.decodeIfPresent(String.self, forKey: .token_type)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        user          = try values.decodeIfPresent(User.self, forKey: .user)
    }
    
}

struct User:Codable {

    let name        :   String?
    let created_at    : String?

    enum CodingKeys : String, CodingKey {

        case name = "name"
        case created_at = "created_at"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        name = try values.decodeIfPresent(String.self, forKey: .name)
        created_at = try values.decodeIfPresent(String.self, forKey: .created_at)
    }
}
