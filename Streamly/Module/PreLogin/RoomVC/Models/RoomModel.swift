//
//  RoomModel.swift
//  Streamly
//
//  Created by Aditya Joshi on 01/10/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import Foundation

// MARK: - RoomAPIReponse
struct RoomAPIReponse: Codable {
    var success: Bool?
    var message: String?
    var responseCode: Int?
    var data: [Room]?

    enum CodingKeys: String, CodingKey {
        case success, message
        case responseCode = "response_code"
        case data
    }
}
