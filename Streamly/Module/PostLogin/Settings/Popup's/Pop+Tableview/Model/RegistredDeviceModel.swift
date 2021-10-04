//
//  RegistredDeviceModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/5/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct DeviceResponseData : Codable {

    let data : [RegistredDevice]?

    enum CodingKeys: String, CodingKey {

        case data = "data"

    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent([RegistredDevice].self, forKey: .data)

    }


}

struct RegistredDevice : Codable {

    let connected_devices : Int?
    let device_id : String?
    let device_name : String?
    let device_img: String?
    
    enum CodingKeys: String, CodingKey {

        case connected_devices = "connected_devices"
        case device_id = "device_id"
        case device_name = "device_name"
        case device_img = "device_img"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        connected_devices = try values.decodeIfPresent(Int.self, forKey: .connected_devices)
        device_id = try values.decodeIfPresent(String.self, forKey: .device_id)
        device_name = try values.decodeIfPresent(String.self, forKey: .device_name)
        device_img = try values.decodeIfPresent(String.self, forKey: .device_img)
    }

}
