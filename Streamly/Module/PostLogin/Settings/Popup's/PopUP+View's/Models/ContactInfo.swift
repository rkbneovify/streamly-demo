//
//  ContactInfo.swift
//  Streamly
//
//  Created by Rohit Singh on 6/10/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct ContactResponse : Codable {

    let data :ContactResponseData?
    let message : String?

    enum CodingKeys: String, CodingKey {

        case data = "data"
        case message = "message"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent(ContactResponseData.self, forKey: .data)
        message = try values.decodeIfPresent(String.self, forKey: .message)

    }


}

struct ContactResponseData : Codable {

    let email : String?
    let num1 : String?
    let num2 : String?

    enum CodingKeys: String, CodingKey {

        case email = "email"
        case num1 = "num1"
        case num2 = "num2"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        email = try values.decodeIfPresent(String.self, forKey: .email)
        num1 = try values.decodeIfPresent(String.self, forKey: .num1)
        num2 = try values.decodeIfPresent(String.self, forKey: .num2)

    }


}

struct ContactData : Codable {

    let connected_devices : Int?
    let device_id : String?
    let device_name : String?

    enum CodingKeys: String, CodingKey {

        case connected_devices = "connected_devices"
        case device_id = "device_id"
        case device_name = "device_name"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        connected_devices = try values.decodeIfPresent(Int.self, forKey: .connected_devices)
        device_id = try values.decodeIfPresent(String.self, forKey: .device_id)
        device_name = try values.decodeIfPresent(String.self, forKey: .device_name)

    }

}
