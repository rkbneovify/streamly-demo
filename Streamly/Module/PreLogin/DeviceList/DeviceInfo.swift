//
//  DeviceInfo.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import Foundation

// MARK: - Loggedindeviceinfolist
struct Loggedindeviceinfolist: Codable {
    let numofdevice: Int?
    let message: String?
    let data: [DeviceInfo]?
}

// MARK: - Datum
struct DeviceInfo: Codable {
    let connectedDevices: Int?
    let deviceID, deviceName, sessionID: String?
    let personaldevicename: String?
    let personaldeviceid: String?
    let deviceImg: String?
    let deviceType: String?

    enum CodingKeys: String, CodingKey {
        case connectedDevices = "connected_devices"
        case deviceID = "device_id"
        case deviceName = "device_name"
        case sessionID = "session_id"
        case personaldevicename, personaldeviceid
        case deviceImg = "device_img"
        case deviceType = "device_type"
    }
}
