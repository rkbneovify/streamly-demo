//
//  RecentData.swift
//  Streamly
//
//  Created by Rohit Singh on 7/28/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation


struct RecentData : Codable {
    
    let data : [RecentDetails]?
    let success:Bool?
    
    enum CodingKeys: String, CodingKey {
        
        case data = "data"
        case success = "success"
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        data = try values.decodeIfPresent([RecentDetails].self, forKey: .data)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        
    }
}

struct RecentDetails : Codable {
    
    let id:Int?
    let name :String?
    let callSign:String?
    
    
    enum CodingKeys: String, CodingKey {
        
        case name = "name"
        case callSign = "callSign"
        case id = "id"
        
        
    }
    
    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        name = try values.decodeIfPresent(String.self, forKey: .name)
        callSign = try values.decodeIfPresent(String.self, forKey: .callSign)
        id = try values.decodeIfPresent(Int.self, forKey: .id)
    }
    
}
