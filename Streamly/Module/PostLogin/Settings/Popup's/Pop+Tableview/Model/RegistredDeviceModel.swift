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

struct UpdatedDeviceInfo: Codable {
    let devicename: String?
    let sessionid: String?
}

struct RegistredDevice : Codable {
    let session_id: String?
    let connected_devices : Int?
    let device_id : String?
    let device_name : String?
    let device_img: String?
    let personaldevicename: String?
    let personaldeviceid: String?
    let deviceType: String?
    let hotelRoom: String?
    
    enum CodingKeys: String, CodingKey {
        case session_id = "session_id"
        case connected_devices = "connected_devices"
        case device_id = "device_id"
        case device_name = "device_name"
        case device_img = "device_img"
        case deviceType = "device_type"
        case personaldevicename
        case personaldeviceid
        case hotelRoom = "hotel_room"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        session_id = try values.decodeIfPresent(String.self, forKey: .session_id)
        connected_devices = try values.decodeIfPresent(Int.self, forKey: .connected_devices)
        device_id = try values.decodeIfPresent(String.self, forKey: .device_id)
        device_name = try values.decodeIfPresent(String.self, forKey: .device_name)
        device_img = try values.decodeIfPresent(String.self, forKey: .device_img)
        personaldevicename = try values.decodeIfPresent(String.self, forKey: .personaldevicename)
        personaldeviceid = try values.decodeIfPresent(String.self, forKey: .personaldeviceid)
        deviceType = try values.decodeIfPresent(String.self, forKey: .deviceType)
        hotelRoom = try values.decodeIfPresent(String.self, forKey: .hotelRoom)
    }

}
